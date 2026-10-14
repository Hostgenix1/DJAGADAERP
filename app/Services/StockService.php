<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
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
    }
}
