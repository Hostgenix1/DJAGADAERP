<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id', 'product_id', 'packaging', 'origin', 'supplier_price',
        'currency_id', 'incoterm', 'destination_port', 'quantity',
        'container_quantity', 'container_type', 'date_received',
        'valid_until', 'source', 'notes',
    ];

    protected $casts = [
        'supplier_price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'container_quantity' => 'decimal:2',
        'date_received' => 'date',
        'valid_until' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function sellingPrices()
    {
        return $this->hasMany(SellingPrice::class);
    }
}
