<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\SupplierBill;
use App\Models\SupplierBillItem;
use App\Support\PaymentTerms;
use Illuminate\Support\Facades\DB;

class SupplierBillService
{
    public function createWithItems(array $data, array $items): SupplierBill
    {
        $data['number'] = SupplierBill::nextNumber();

        return DB::transaction(function () use ($data, $items) {
            $bill = SupplierBill::create($data);
            $this->syncItems($bill, $items);
            $bill->recalculate();
            return $bill;
        });
    }

    public function updateWithItems(SupplierBill $bill, array $data, array $items): SupplierBill
    {
        return DB::transaction(function () use ($bill, $data, $items) {
            $bill->update($data);
            $bill->items()->delete();
            $this->syncItems($bill, $items);
            $bill->recalculate();
            return $bill;
        });
    }

    public function convertFromPo(PurchaseOrder $po): SupplierBill
    {
        return DB::transaction(function () use ($po) {
            $po->refresh();
            if ($po->status === 'billed') {
                throw new \Exception('This purchase order has already been billed.');
            }

            $defaultTerms = $po->supplier?->default_payment_term
                ?? PaymentTerms::defaultFor('supplier_bill');

            $bill = SupplierBill::create([
                'number' => SupplierBill::nextNumber(),
                'supplier_id' => $po->supplier_id,
                'purchase_order_id' => $po->id,
                'currency_id' => $po->currency_id,
                'bill_date' => now(),
                'due_date' => now()->addDays(30),
                'status' => 'draft',
                'payment_terms' => $po->payment_terms ?: $defaultTerms,
                'reference_no' => $po->reference_no,
                'vat_mode' => $po->vat_mode ?? 'excluded',
                'vat_rate' => $po->vat_rate,
                'subtotal' => $po->subtotal,
                'tax_amount' => $po->tax_amount,
                'discount' => $po->discount,
                'total' => $po->total,
                'notes' => $po->notes,
            ]);

            foreach ($po->items as $item) {
                SupplierBillItem::create([
                    'supplier_bill_id' => $bill->id,
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'sub_description' => $item->sub_description,
                    'qty' => $item->qty,
                    'unit' => $item->unit,
                    'unit_price' => $item->unit_price,
                    'tax_rate' => $item->tax_rate,
                    'discount_pct' => $item->discount_pct,
                    'line_total' => $item->line_total,
                ]);
            }

            $po->update(['status' => 'billed']);

            return $bill;
        });
    }

    private function syncItems(SupplierBill $bill, array $items): void
    {
        foreach ($items as $item) {
            $item['sub_description'] = $item['sub_description'] ?? null;
            if (empty($item['tax_rate'])) {
                $item['tax_rate'] = null;
            }
            $bill->items()->create($item);
        }
    }

    public function query()
    {
        return SupplierBill::with('supplier')->latest('id');
    }
}
