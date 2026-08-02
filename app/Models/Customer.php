<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Currency;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'company_name', 'contact_person', 'email', 'phone', 'address', 'city', 'country', 'currency_id', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function documents()
    {
        return $this->morphMany(\App\Models\Document::class, 'documentable');
    }

    public function communications()
    {
        return $this->morphMany(\App\Models\Communication::class, 'communicable');
    }

    public function follow_ups()
    {
        return $this->morphMany(\App\Models\FollowUp::class, 'followable');
    }

    public function quotes()
    {
        return $this->hasMany(\App\Models\Quote::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    public function leads()
    {
        return $this->hasMany(\App\Models\Lead::class);
    }
}
