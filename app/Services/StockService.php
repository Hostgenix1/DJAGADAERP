<?php

namespace App\Services;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    /**
     * Current stock for a product, optionally scoped to one warehouse.
     */
    public static function currentStock(int $productId, ?int $warehouseId = null): float
    {
        $query = StockMovement::where('product_id', $productId);
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        return (float) $query->sum('qty');
    }

    /**
     * Products whose total stock is at or below their reorder level.
     * Returns collection of ['product' => Product, 'stock' => float].
     */
    public static function lowStockProducts()
    {
        return Product::where('is_active', true)
            ->whereNotNull('reorder_level')
            ->where('reorder_level', '>', 0)
            ->withSum('stockMovements as stock_total', 'qty')
            ->get()
            ->map(fn (Product $p) => ['product' => $p, 'stock' => (float) ($p->stock_total ?? 0)])
            ->filter(fn ($row) => $row['stock'] <= (float) $row['product']->reorder_level)
            ->values();
    }

    /**
     * Whole-stock valuation at cost (buy_price) per product.
     */
    public static function stockValue(): float
    {
        $rows = StockMovement::query()
            ->join('products', 'products.id', '=', 'stock_movements.product_id')
            ->selectRaw('stock_movements.product_id, SUM(stock_movements.qty) as qty, MAX(products.buy_price) as buy_price')
            ->groupBy('stock_movements.product_id')
            ->get();

        return round($rows->sum(fn ($r) => max(0, (float) $r->qty) * (float) $r->buy_price), 2);
    }

    /**
     * Record a movement with negative-stock protection.
     * $qty is always entered positive; direction comes from $type:
     *  - in: +qty, out: -qty (blocked if it would drive stock below zero),
     *  - adjustment: signed $qty (positive or negative correction).
     */
    public static function record(array $data): StockMovement
    {
        $type = $data['type'];
        $qty = (float) $data['qty'];

        if ($type === 'out') {
            $qty = -abs($qty);
        } elseif ($type === 'in') {
            $qty = abs($qty);
        } // adjustment keeps the sign as entered

        return DB::transaction(function () use ($data, $type, $qty) {
            // Lock the product row so two concurrent issues cannot both pass
            // the availability check and drive stock below zero.
            Product::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();

            $available = self::currentStock((int) $data['product_id'], (int) $data['warehouse_id']);
            $after = round($available + $qty, 2);

            if ($after < 0) {
                throw ValidationException::withMessages([
                    'qty' => 'Not enough stock: available '.number_format($available, 2).', requested '.number_format(abs($qty), 2).'.',
                ]);
            }

            return StockMovement::create([
                'product_id' => $data['product_id'],
                'warehouse_id' => $data['warehouse_id'],
                'qty' => $qty,
                'type' => $type,
                'unit_cost' => $data['unit_cost'] ?? null,
                'reference' => $data['reference'] ?? null,
                'movement_date' => $data['movement_date'],
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });
    }

    /**
     * Goods receipt for a purchase order: adds one 'in' movement per product
     * line into the first active warehouse (unless one is given). Each receipt
     * cycle is tagged with its own reference (PO:<number>:R<n>) so receipts and
     * their cancellations stay traceable in the stock ledger.
     */
    public static function receiveFromPurchaseOrder(PurchaseOrder $po, ?int $warehouseId = null): void
    {
        $warehouseId = $warehouseId ?: self::firstActiveWarehouseId();

        if (! $warehouseId) {
            throw new \InvalidArgumentException('Create an active warehouse before receiving goods.');
        }

        $cycle = self::purchaseOrderCycles($po)->count() + 1;
        $reference = 'PO:'.$po->number.':R'.$cycle;

        DB::transaction(function () use ($po, $warehouseId, $reference) {
            foreach ($po->items()->whereNotNull('product_id')->oldest('id')->get() as $item) {
                if ((float) $item->qty === 0.0) {
                    continue;
                }

                self::record([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $warehouseId,
                    'qty' => abs((float) $item->qty),
                    'type' => 'in',
                    'unit_cost' => $item->unit_price,
                    'reference' => $reference,
                    'movement_date' => now()->toDateString(),
                    'notes' => 'Goods receipt for '.$po->number,
                ]);
            }
        });
    }

    /**
     * Reverses the latest un-reversed goods receipt of a purchase order when it
     * is cancelled after being marked received. Safe to call twice.
     */
    public static function reverseLatestPurchaseOrderReceipt(PurchaseOrder $po): void
    {
        $cycles = self::purchaseOrderCycles($po);

        if ($cycles->isEmpty()) {
            return;
        }

        $latestReceipt = $cycles->sortByDesc('cycle')->first();
        $receiptReference = 'PO:'.$po->number.':R'.$latestReceipt['cycle'];
        $cancelReference = 'PO:'.$po->number.':C'.$latestReceipt['cycle'];

        if (StockMovement::where('reference', $cancelReference)->exists()) {
            return;
        }

        DB::transaction(function () use ($po, $receiptReference, $cancelReference) {
            foreach (StockMovement::where('reference', $receiptReference)->get() as $movement) {
                if ((float) $movement->qty === 0.0) {
                    continue;
                }

                self::record([
                    'product_id' => $movement->product_id,
                    'warehouse_id' => $movement->warehouse_id,
                    'qty' => -abs((float) $movement->qty),
                    'type' => 'adjustment', // adjustment keeps the sign: this drives stock down
                    'unit_cost' => $movement->unit_cost,
                    'reference' => $cancelReference,
                    'movement_date' => now()->toDateString(),
                    'notes' => 'Reversal of goods receipt for cancelled '.$po->number,
                ]);
            }
        });
    }

    /**
     * Receipt cycles of a purchase order found in the stock ledger:
     * [['cycle' => n, 'reference' => 'PO:<number>:R<n>'], ...]
     */
    private static function purchaseOrderCycles(PurchaseOrder $po): Collection
    {
        return StockMovement::where('reference', 'like', 'PO:'.$po->number.':R%')
            ->pluck('reference')
            ->unique()
            ->map(function (string $reference) {
                preg_match('/:R(\d+)$/', $reference, $m);

                return ['cycle' => (int) ($m[1] ?? 0), 'reference' => $reference];
            })
            ->values();
    }

    private static function firstActiveWarehouseId(): ?int
    {
        return Warehouse::where('is_active', true)->orderBy('id')->value('id');
    }
}
