<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceService
{
    public function createWithItems(array $data, array $items): Invoice
    {
        $data['number'] = Invoice::nextNumber($data['type'] ?? 'commercial');
        $invoice = Invoice::create($data);

        foreach ($items as $item) {
            $invoice->items()->create($item);
        }

        $invoice->recalculate();

        return $invoice;
    }

    public function updateWithItems(Invoice $invoice, array $data, array $items): Invoice
    {
        $invoice->update($data);
        $invoice->items()->delete();

        foreach ($items as $item) {
            $invoice->items()->create($item);
        }

        $invoice->recalculate();

        return $invoice;
    }

    public function query()
    {
        return Invoice::with('customer')->latest('id');
    }
}
