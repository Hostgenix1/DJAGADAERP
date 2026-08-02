<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Currency;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $table = 'leads';

    protected $fillable = [
        'company_name', 'contact_name', 'email', 'phone', 'source', 'status', 'expected_amount', 'currency_id', 'expected_date', 'owner_id', 'customer_id', 'note'
    ];

    protected $casts = [
        'expected_amount' => 'decimal:2',
        'expected_date' => 'date',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
