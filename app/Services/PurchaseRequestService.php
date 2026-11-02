<?php

namespace App\Services;

use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\DB;

class PurchaseRequestService
{
    public function createWithItems(array $data, array $items): PurchaseRequest
    {
        return DB::transaction(function () use ($data, $items) {
            $data['number'] = PurchaseRequest::nextNumber();
            $request = PurchaseRequest::create($data);

            $this->syncItems($request, $items);
            $request->recalculate();

            return $request;
        });
    }

    public function updateWithItems(PurchaseRequest $request, array $data, array $items): PurchaseRequest
    {
        return DB::transaction(function () use ($request, $data, $items) {
            $request->update($data);
            $request->items()->delete();
            $request->unsetRelation('items');

            $this->syncItems($request, $items);
            $request->recalculate();

            return $request;
        });
    }

    public function query()
    {
        return PurchaseRequest::with(['requester', 'supplier', 'currency'])->latest('id');
    }

    private function syncItems(PurchaseRequest $request, array $items): void
    {
        foreach ($items as $item) {
            $request->items()->create([
                'product_id' => $item['product_id'] ?? null,
                'description' => $item['description'],
                'qty' => $item['qty'],
                'unit' => $item['unit'] ?? 'pc',
                'unit_price' => $item['unit_price'] ?? 0,
            ]);
        }
    }
}
