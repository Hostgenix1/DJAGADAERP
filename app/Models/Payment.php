<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'type', 'customer_id', 'supplier_id', 'currency_id',
        'method', 'amount', 'rate', 'paid_on', 'reference', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'rate' => 'decimal:4',
        'paid_on' => 'date',
    ];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
    public function invoices(): BelongsToMany { return $this->belongsToMany(Invoice::class, 'invoice_payments')->withPivot('amount'); }
    public function documents() { return $this->morphMany(Document::class, 'documentable'); }

    public static function nextNumber(): string
    {
        $last = self::orderBy('id', 'desc')->value('number');
        $next = 1;
        if ($last && preg_match('/PAY-(\d+)/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }

        return 'PAY-'.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getMethodsAttribute(): array
    {
        return ['cash', 'bank', 'cheque', 'mobile', 'transfer'];
    }
}
