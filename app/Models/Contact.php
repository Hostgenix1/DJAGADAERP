<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'customer_id', 'full_name', 'email', 'phone', 'position', 'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Contact $contact) {
            if ($contact->is_primary && $contact->customer_id) {
                static::where('customer_id', $contact->customer_id)
                    ->where('id', '!=', $contact->id)
                    ->update(['is_primary' => false]);
            }
        });

        static::deleted(function (Contact $contact) {
            if ($contact->is_primary && $contact->customer_id) {
                $nextPrimary = static::where('customer_id', $contact->customer_id)->first();
                if ($nextPrimary) {
                    $nextPrimary->update(['is_primary' => true]);
                }
            }
        });
    }
}
