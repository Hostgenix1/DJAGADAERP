<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'sku', 'name', 'description', 'brand_id', 'category_id', 'supplier_id', 'buy_price', 'sell_price', 'reorder_level', 'currency_id', 'tax_id', 'unit', 'pack_qty', 'pack_type', 'weight_kg', 'dimensions', 'length_cm', 'width_cm', 'height_cm', 'hs_code', 'country_of_origin', 'specifications', 'certificates', 'is_active',
    ];

    protected $casts = [
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'weight_kg' => 'decimal:3',
        'length_cm' => 'decimal:2',
        'width_cm' => 'decimal:2',
        'height_cm' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
