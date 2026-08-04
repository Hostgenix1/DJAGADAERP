<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Quote;
use App\Models\QuoteItem;

class QuoteService
{
    public function createWithItems(array $data, array $items): Quote
    {
        $data['number'] = Quote::nextNumber();
        $quote = Quote::create($data);

        foreach ($items as $item) {
            $quote->items()->create($item);
        }

        $quote->recalculate();

        return $quote;
    }

    public function updateWithItems(Quote $quote, array $data, array $items): Quote
    {
        $quote->update($data);

        $quote->items()->delete();
        foreach ($items as $item) {
            $quote->items()->create($item);
        }

        $quote->recalculate();

        return $quote;
    }

    public function createRevision(Quote $original): Quote
    {
        $new = $original->replicate();
        $new->number = Quote::nextNumber();
        $new->revision = ($original->revision ?? 0) + 1;
        $new->status = 'draft';
        $new->save();

        foreach ($original->items as $item) {
            $newItem = $item->replicate();
            $newItem->quote_id = $new->id;
            $newItem->save();
        }

        $new->recalculate();

        return $new;
    }

    public function convertToInvoice(Quote $quote, string $type = 'proforma'): Invoice
    {
        if ($quote->status === 'converted') {
            throw new \Exception('This quote has already been converted.');
        }

        $invoice = Invoice::create([
            'number' => Invoice::nextNumber($type),
            'type' => $type,
            'customer_id' => $quote->customer_id,
            'currency_id' => $quote->currency_id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'draft',
            'subtotal' => $quote->subtotal,
            'tax_amount' => $quote->tax_amount,
            'discount' => $quote->discount,
            'total' => $quote->total,
            'notes' => $quote->notes,
            'quote_id' => $quote->id,
        ]);

        foreach ($quote->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item->product_id,
                'description' => $item->description,
                'qty' => $item->qty,
                'unit' => $item->unit,
                'unit_price' => $item->unit_price,
                'tax_rate' => $item->tax_rate,
                'discount_pct' => $item->discount_pct,
                'line_total' => $item->line_total,
            ]);
        }

        $quote->update(['status' => 'converted']);

        return $invoice;
    }

    public function query()
    {
        return Quote::with('customer')->latest('id');
    }
}
