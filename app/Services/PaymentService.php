<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function query()
    {
        return Payment::with(['customer', 'supplier', 'currency', 'invoices']);
    }

    public function createWithAllocation(array $data, array $allocations): Payment
    {
        return DB::transaction(function () use ($data, $allocations) {
            $totalAllocated = collect($allocations)->sum('amount');
            if ($totalAllocated > ($data['amount'] ?? 0)) {
                throw new \InvalidArgumentException('Total allocations exceed payment amount.');
            }

            $payment = Payment::create($data);

            foreach ($allocations as $alloc) {
                $invoice = Invoice::findOrFail($alloc['invoice_id']);
                if ($invoice->status === 'cancelled') {
                    throw new \InvalidArgumentException("Cannot allocate to a cancelled invoice ({$invoice->number}).");
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
                $pivotAmount = $payment->invoices->where('id', $invoice->id)->first()->pivot->amount ?? 0;
                $invoice->decrement('paid_amount', $pivotAmount);
                $invoice->refresh();
                if ($invoice->paid_amount <= 0) {
                    $invoice->update(['status' => 'sent']);
                } elseif ($invoice->paid_amount < $invoice->total) {
                    $invoice->update(['status' => 'partial']);
                }
            }
            $payment->invoices()->detach();
        });
    }
}
