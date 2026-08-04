<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;

    protected $table = 'currencies';

    protected $fillable = [
        'code', 'name', 'symbol', 'rate', 'is_active', 'is_default'
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function customers()
    {
        return $this->hasMany(\App\Models\Customer::class);
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class);
    }

    public function quotes()
    {
        return $this->hasMany(\App\Models\Quote::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(\App\Models\CompanyBankAccount::class);
    }
}
