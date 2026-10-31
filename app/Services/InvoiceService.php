<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function createWithItems(array $data, array $items): Invoice
    {
        // The number is generated from the last invoice, so a concurrent
        // create can collide with the unique index. Retry a couple of times
        // before surfacing the error.
        for ($attempt = 1; ; $attempt++) {
            try {
                return DB::transaction(function () use ($data, $items) {
                    $data['number'] = Invoice::nextNumber($data['type'] ?? 'commercial');
                    $invoice = Invoice::create($data);

                    $this->syncItems($invoice, $items);
                    $invoice->recalculate();

                    return $invoice;
                });
            } catch (QueryException $e) {
                if ($attempt >= 3 || ! $this->isDuplicateNumber($e)) {
                    throw $e;
                }
            }
        }
    }

    public function updateWithItems(Invoice $invoice, array $data, array $items): Invoice
    {
        return DB::transaction(function () use ($invoice, $data, $items) {
            $invoice->update($data);
            $invoice->items()->delete();
            $invoice->unsetRelation('items');

            $this->syncItems($invoice, $items);
            $invoice->recalculate();

            return $invoice;
        });
    }

    private function isDuplicateNumber(QueryException $e): bool
    {
        return (string) $e->getCode() === '23000'
            && str_contains(strtolower($e->getMessage()), 'number');
    }

    private function syncItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $item) {
            $item['sub_description'] = $item['sub_description'] ?? null;
            if (! isset($item['tax_rate']) || $item['tax_rate'] === '') {
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
