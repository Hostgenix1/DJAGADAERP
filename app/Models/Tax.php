<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'rate',
        'kind',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:3',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }

    public function scopeSales($query)
    {
        return $query->where('kind', 'sales');
    }

    public function scopePurchases($query)
    {
        return $query->where('kind', 'purchase');
    }
}