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

        $this->syncItems($invoice, $items);
        $invoice->recalculate();

        return $invoice;
    }

    public function updateWithItems(Invoice $invoice, array $data, array $items): Invoice
    {
        $invoice->update($data);
        $invoice->items()->delete();

        $this->syncItems($invoice, $items);
        $invoice->recalculate();

        return $invoice;
    }

    private function syncItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $item) {
            $item['sub_description'] = $item['sub_description'] ?? null;
            if (!isset($item['tax_rate']) || $item['tax_rate'] === '') {
                $item['tax_rate'] = null;
            }
            $invoice->items()->create($item);
        }
    }

    public function query()
    {
        return Invoice::with('customer')->latest('id');
    }
}
