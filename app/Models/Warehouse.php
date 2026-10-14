<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'location', 'is_active', 'notes'];

    protected $casts = ['is_active' => 'boolean'];

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
