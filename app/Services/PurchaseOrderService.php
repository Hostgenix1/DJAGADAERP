<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    public function createWithItems(array $data, array $items): PurchaseOrder
    {
        $data['number'] = PurchaseOrder::nextNumber();

        return DB::transaction(function () use ($data, $items) {
            $po = PurchaseOrder::create($data);
            $this->syncItems($po, $items);
            $po->recalculate();
            return $po;
        });
    }

    public function updateWithItems(PurchaseOrder $po, array $data, array $items): PurchaseOrder
    {
        return DB::transaction(function () use ($po, $data, $items) {
            $po->update($data);
            $po->items()->delete();
            $this->syncItems($po, $items);
            $po->recalculate();
            return $po;
        });
    }

    private function syncItems(PurchaseOrder $po, array $items): void
    {
        foreach ($items as $item) {
            $item['sub_description'] = $item['sub_description'] ?? null;
            if (!isset($item['tax_rate']) || $item['tax_rate'] === '') {
                $item['tax_rate'] = null;
            }
            $po->items()->create($item);
        }
    }

    public function query()
    {
        return PurchaseOrder::with(['supplier', 'currency'])->latest('id');
    }
}
