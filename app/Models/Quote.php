<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'customer_id', 'currency_id', 'date', 'valid_until', 'status', 'revision',
        'subtotal', 'tax_amount', 'discount', 'total', 'notes', 'terms',
    ];

    protected $casts = [
        'date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'revision' => 'integer',
    ];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
    public function items(): HasMany { return $this->hasMany(QuoteItem::class); }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class);
    }

    public static function nextNumber(): string
    {
        $last = self::orderBy('id', 'desc')->value('number');
        $next = 1;
        if ($last && preg_match('/QT-(\d+)/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }

        return 'QT-'.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'sent' => 'badge-info',
            'accepted' => 'badge-success',
            'rejected' => 'badge-danger',
            'converted' => 'badge-primary',
            default => 'badge-light',
        };
    }

    public function recalculate(): void
    {
        $this->subtotal = $this->items->sum('line_total');
        $this->tax_amount = $this->items->sum(fn ($i) => $i->line_total * $i->tax_rate / 100);
        $this->total = $this->subtotal + $this->tax_amount - $this->discount;
        $this->save();
    }
}
