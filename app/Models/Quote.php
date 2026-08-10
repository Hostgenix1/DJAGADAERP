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
        'reference_no', 'payment_terms', 'delivery_terms',
        'port_of_loading', 'port_of_discharge', 'goods_origin',
        'offer_valid', 'vat_mode', 'vat_rate',
    ];

    protected $casts = [
        'date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'revision' => 'integer',
        'offer_valid' => 'integer',
        'vat_rate' => 'decimal:3',
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
        $year = now()->format('Y');
        $last = self::withTrashed()
            ->where('number', 'like', 'QT/'.$year.'/%')
            ->orderByDesc('id')
            ->value('number');

        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }

        return 'QT/'.$year.'/'.str_pad($next, 5, '0', STR_PAD_LEFT);
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
        $items = $this->items->loadMissing('product');

        $subtotal = $items->sum('line_total');

        if ($this->vat_mode === 'included') {
            $tax = $items->sum(function ($i) {
                $rate = $i->tax_rate !== null ? (float) $i->tax_rate : (float) ($this->vat_rate ?? 0);
                return (float) $i->line_total * $rate / (100 + $rate);
            });
            $this->subtotal = round($subtotal - $tax, 2);
            $this->tax_amount = round($tax, 2);
            $this->total = round($subtotal - $this->discount, 2);
        } else {
            $tax = $items->sum(function ($i) {
                $rate = $i->tax_rate !== null ? (float) $i->tax_rate : (float) ($this->vat_rate ?? 0);
                return (float) $i->line_total * $rate / 100;
            });
            $this->subtotal = round($subtotal, 2);
            $this->tax_amount = $this->vat_mode === 'none' ? 0 : round($tax, 2);
            $this->total = round($this->subtotal + $this->tax_amount - $this->discount, 2);
        }

        $this->save();
    }
}
