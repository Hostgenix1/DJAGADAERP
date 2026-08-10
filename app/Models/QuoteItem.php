<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    protected $fillable = [
        'quote_id', 'product_id', 'description', 'sub_description', 'qty', 'unit', 'unit_price', 'tax_rate', 'discount_pct', 'line_total',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:3',
        'discount_pct' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function quote(): BelongsTo { return $this->belongsTo(Quote::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }

    public static function boot(): void
    {
        parent::boot();

        static::saving(function (QuoteItem $item) {
            $base = $item->qty * $item->unit_price;
            $item->line_total = $base - ($base * $item->discount_pct / 100);
        });
    }
}
