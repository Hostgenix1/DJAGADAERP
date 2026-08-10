<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SupplierBill;
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

            $data['number'] = \App\Models\Payment::nextNumber();
            $payment = Payment::create($data);

            $isSupplier = ($data['type'] ?? 'customer') === 'supplier';

            foreach ($allocations as $alloc) {
                if ($isSupplier && !empty($alloc['supplier_bill_id'])) {
                    $bill = SupplierBill::findOrFail($alloc['supplier_bill_id']);
                    if ($bill->status === 'cancelled' || $bill->status === 'draft') {
                        throw new \InvalidArgumentException("Cannot allocate to a {$bill->status} supplier bill ({$bill->number}).");
                    }
                    $remainingBalance = $bill->total - $bill->paid_amount;
                    if ($alloc['amount'] > $remainingBalance) {
                        throw new \InvalidArgumentException("Allocation amount ({$alloc['amount']}) exceeds remaining balance ({$remainingBalance}) for bill {$bill->number}.");
                    }
                    $bill->increment('paid_amount', $alloc['amount']);
                    $bill->refresh();
                    if ($bill->paid_amount >= $bill->total) {
                        $bill->update(['status' => 'paid']);
                    } elseif ($bill->paid_amount > 0) {
                        $bill->update(['status' => 'partial']);
                    }
                    $payment->supplierBills()->attach($bill->id, ['amount' => $alloc['amount']]);
                    continue;
                }

                $invoice = Invoice::findOrFail($alloc['invoice_id']);
                if ($invoice->status === 'cancelled') {
                    throw new \InvalidArgumentException("Cannot allocate to a cancelled invoice ({$invoice->number}).");
                }
                $remainingBalance = $invoice->total - $invoice->paid_amount;
                if ($alloc['amount'] > $remainingBalance) {
                    throw new \InvalidArgumentException("Allocation amount ({$alloc['amount']}) exceeds remaining balance ({$remainingBalance}) for invoice {$invoice->number}.");
                }
                $invoice->increment('paid_amount', $alloc['amount']);
                $invoice->refresh();
                if ($invoice->paid_amount >= $invoice->total) {
                    $invoice->update(['status' => 'paid']);
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->update(['status' => 'partial']);
                }
                $payment->invoices()->attach($invoice->id, ['amount' => $alloc['amount']]);
            }

            return $payment;
        });
    }

    public static function reverseAllocations(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            foreach ($payment->invoices as $invoice) {
                $pivotAmount = $invoice->pivot->amount ?? 0;
                $invoice->decrement('paid_amount', $pivotAmount);
                $invoice->refresh();
                if ($invoice->paid_amount <= 0) {
                    $invoice->update(['status' => 'draft']);
                } elseif ($invoice->paid_amount < $invoice->total) {
                    $invoice->update(['status' => 'partial']);
                }
            }
            $payment->invoices()->detach();

            foreach ($payment->supplierBills as $bill) {
                $pivotAmount = $bill->pivot->amount ?? 0;
                $bill->decrement('paid_amount', $pivotAmount);
                $bill->refresh();
                if ($bill->paid_amount <= 0) {
                    $bill->update(['status' => 'confirmed']);
                } elseif ($bill->paid_amount < $bill->total) {
                    $bill->update(['status' => 'partial']);
                }
            }
            $payment->supplierBills()->detach();
        });
    }
}
