<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SupplierBill;
use App\Support\CurrencyHelper;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function query()
    {
        return Payment::with(['customer', 'supplier', 'currency', 'invoices', 'supplierBills']);
    }

    public function createWithAllocation(array $data, array $allocations): Payment
    {
        return DB::transaction(function () use ($data, $allocations) {
            $totalAllocated = collect($allocations)->sum('amount');
            if ($totalAllocated > ($data['amount'] ?? 0)) {
                throw new \InvalidArgumentException('Total allocations exceed payment amount.');
            }

            $isSupplier = ($data['type'] ?? 'customer') === 'supplier';
            foreach ($allocations as $alloc) {
                if ($isSupplier && ! empty($alloc['invoice_id'])) {
                    throw new \InvalidArgumentException('Supplier payments can only be allocated to supplier bills.');
                }
                if (! $isSupplier && ! empty($alloc['supplier_bill_id'])) {
                    throw new \InvalidArgumentException('Customer payments can only be allocated to invoices.');
                }
                if (empty($alloc['invoice_id']) && empty($alloc['supplier_bill_id'])) {
                    throw new \InvalidArgumentException('Each allocation must target an invoice or a supplier bill.');
                }
            }

            $paymentCurrencyId = $data['currency_id'] ?? null;
            $data['number'] = \App\Models\Payment::nextNumber();
            $payment = Payment::create($data);

            foreach ($allocations as $alloc) {
                if ($isSupplier && ! empty($alloc['supplier_bill_id'])) {
                    $bill = SupplierBill::whereKey($alloc['supplier_bill_id'])->lockForUpdate()->first();
                    $billLabel = $bill?->number ?? 'unknown';
                    if (! $bill || $bill->status === 'cancelled' || $bill->status === 'draft') {
                        throw new \InvalidArgumentException("Cannot allocate to a non-confirmed supplier bill ({$billLabel}).");
                    }
                    if (! empty($data['supplier_id']) && (int) $bill->supplier_id !== (int) $data['supplier_id']) {
                        throw new \InvalidArgumentException("Bill {$bill->number} does not belong to the selected supplier.");
                    }
                    $remainingBase = $this->balanceInBase($bill->total - $bill->paid_amount, $bill->currency_id);
                    $allocBase = $this->amountInBase($alloc['amount'], $paymentCurrencyId);
                    if ($allocBase > $remainingBase + 0.005) {
                        throw new \InvalidArgumentException("Allocation amount exceeds remaining balance for bill {$bill->number}.");
                    }
                    $previousStatus = $bill->status;
                    $bill->increment('paid_amount', $this->toTargetCurrency($alloc['amount'], $paymentCurrencyId, $bill->currency_id));
                    $bill->refresh();
                    if ($bill->paid_amount >= $bill->total) {
                        $bill->update(['status' => 'paid']);
                    } elseif ($bill->paid_amount > 0) {
                        $bill->update(['status' => 'partial']);
                    }
                    $payment->supplierBills()->attach($bill->id, ['amount' => $this->toTargetCurrency($alloc['amount'], $paymentCurrencyId, $bill->currency_id), 'previous_status' => $previousStatus]);
                    continue;
                }

                $invoice = Invoice::whereKey($alloc['invoice_id'])->lockForUpdate()->first();
                $invoiceLabel = $invoice?->number ?? 'unknown';
                if (! $invoice || $invoice->status === 'cancelled') {
                    throw new \InvalidArgumentException("Cannot allocate to a cancelled invoice ({$invoiceLabel}).");
                }
                if (! empty($data['customer_id']) && (int) $invoice->customer_id !== (int) $data['customer_id']) {
                    throw new \InvalidArgumentException("Invoice {$invoice->number} does not belong to the selected customer.");
                }
                $remainingBase = $this->balanceInBase($invoice->total - $invoice->paid_amount, $invoice->currency_id);
                $allocBase = $this->amountInBase($alloc['amount'], $paymentCurrencyId);
                if ($allocBase > $remainingBase + 0.005) {
                    throw new \InvalidArgumentException("Allocation amount exceeds remaining balance for invoice {$invoice->number}.");
                }
                $previousStatus = $invoice->status;
                $invoice->increment('paid_amount', $this->toTargetCurrency($alloc['amount'], $paymentCurrencyId, $invoice->currency_id));
                $invoice->refresh();
                if ($invoice->paid_amount >= $invoice->total) {
                    $invoice->update(['status' => 'paid']);
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->update(['status' => 'partial']);
                }
                $payment->invoices()->attach($invoice->id, ['amount' => $this->toTargetCurrency($alloc['amount'], $paymentCurrencyId, $invoice->currency_id), 'previous_status' => $previousStatus]);
            }

            return $payment;
        });
    }

    public static function reverseAllocations(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            foreach ($payment->invoices as $invoice) {
                $locked = Invoice::whereKey($invoice->id)->lockForUpdate()->first();
                if (! $locked) {
                    continue;
                }
                $pivotAmount = $invoice->pivot->amount ?? 0;
                $previousStatus = $invoice->pivot->previous_status;
                $locked->update(['paid_amount' => max(0, (float) $locked->paid_amount - (float) $pivotAmount)]);
                $locked->refresh();
                $locked->update(['status' => self::restoreStatus($locked, $previousStatus, 'sent')]);
            }
            $payment->invoices()->detach();

            foreach ($payment->supplierBills as $bill) {
                $locked = SupplierBill::whereKey($bill->id)->lockForUpdate()->first();
                if (! $locked) {
                    continue;
                }
                $pivotAmount = $bill->pivot->amount ?? 0;
                $previousStatus = $bill->pivot->previous_status;
                $locked->update(['paid_amount' => max(0, (float) $locked->paid_amount - (float) $pivotAmount)]);
                $locked->refresh();
                $locked->update(['status' => self::restoreStatus($locked, $previousStatus, 'confirmed')]);
            }
            $payment->supplierBills()->detach();
        });
    }

    private static function restoreStatus($model, ?string $previousStatus, string $fallback): string
    {
        if (! empty($previousStatus) && ! in_array($previousStatus, ['paid', 'partial'], true)) {
            return $previousStatus;
        }

        if ($model->paid_amount <= 0) {
            return $fallback;
        }

        if ($model->paid_amount < $model->total) {
            return 'partial';
        }

        return 'paid';
    }

    private function balanceInBase(float $balance, ?int $currencyId): float
    {
        return (float) $balance / CurrencyHelper::rateOf($currencyId);
    }

    private function amountInBase(float $amount, ?int $paymentCurrencyId): float
    {
        return (float) $amount / CurrencyHelper::rateOf($paymentCurrencyId);
    }

    private function toTargetCurrency(float $amount, ?int $fromCurrencyId, ?int $toCurrencyId): float
    {
        return round(CurrencyHelper::convert($amount, $fromCurrencyId, $toCurrencyId), 2);
    }
}
