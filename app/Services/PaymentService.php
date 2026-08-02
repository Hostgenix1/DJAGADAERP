<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;

class PaymentService
{
    public function createWithAllocation(array $data, array $invoiceAllocations = []): Payment
    {
        $data['number'] = Payment::nextNumber();
        $payment = Payment::create($data);

        foreach ($invoiceAllocations as $alloc) {
            $invoice = Invoice::find($alloc['invoice_id']);
            if ($invoice) {
                $payment->invoices()->attach($invoice->id, ['amount' => $alloc['amount']]);
                $invoice->paid_amount += $alloc['amount'];
                $invoice->status = $invoice->paid_amount >= $invoice->total ? 'paid' : 'partial';
                $invoice->save();
            }
        }

        return $payment;
    }

    public function query()
    {
        return Payment::with(['customer', 'supplier'])->latest('id');
    }
}
