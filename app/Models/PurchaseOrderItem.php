<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id', 'product_id', 'description', 'sub_description',
        'qty', 'unit', 'unit_price', 'tax_rate', 'discount_pct', 'line_total',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:3',
        'discount_pct' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function purchaseOrder(): BelongsTo { return $this->belongsTo(PurchaseOrder::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $qty = (float) $item->qty;
            $price = (float) $item->unit_price;
            $disc = (float) ($item->discount_pct ?? 0);
            $item->line_total = round($qty * $price * (1 - $disc / 100), 2);
        });
    }
}
