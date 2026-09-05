<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierBill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'supplier_id', 'purchase_order_id', 'currency_id',
        'bill_date', 'due_date', 'status', 'payment_terms', 'reference_no',
        'vat_mode', 'vat_rate', 'subtotal', 'tax_amount', 'discount',
        'total', 'paid_amount', 'notes',
    ];

    protected $attributes = [
        'status' => 'draft',
        'vat_mode' => 'excluded',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'vat_rate' => 'decimal:3',
    ];

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function purchaseOrder(): BelongsTo { return $this->belongsTo(PurchaseOrder::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
    public function items(): HasMany { return $this->hasMany(SupplierBillItem::class); }
    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'supplier_bill_payments')->withPivot('amount');
    }

    public function documents() { return $this->morphMany(Document::class, 'documentable'); }

    public static function nextNumber(): string
    {
        $year = now()->format('Y');
        $last = self::where('number', 'like', 'SB/'.$year.'/%')
            ->withTrashed()
            ->orderByDesc('id')
            ->value('number');

        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }

        return 'SB/'.$year.'/'.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'confirmed' => 'badge-info',
            'paid' => 'badge-success',
            'partial' => 'badge-warning',
            'cancelled' => 'badge-dark',
            default => 'badge-light',
        };
    }

    public function getBalanceAttribute(): float
    {
        return (float) $this->total - (float) $this->paid_amount;
    }

    public function recalculate(): void
    {
        $items = $this->items;

        $subtotal = $items->sum('line_total');

        if ($this->vat_mode === 'included') {
            $tax = $items->sum(function ($i) {
                $rate = $i->tax_rate !== null ? (float) $i->tax_rate : (float) ($this->vat_rate ?? 0);
                return (float) $i->line_total * $rate / (100 + $rate);
            });
            $this->subtotal = round($subtotal - $tax, 2);
            $this->tax_amount = round($tax, 2);
            $this->total = max(0, round($subtotal - $this->discount, 2));
        } else {
            $tax = $items->sum(function ($i) {
                $rate = $i->tax_rate !== null ? (float) $i->tax_rate : (float) ($this->vat_rate ?? 0);
                return (float) $i->line_total * $rate / 100;
            });
            $this->subtotal = round($subtotal, 2);
            $this->tax_amount = $this->vat_mode === 'none' ? 0 : round($tax, 2);
            $this->total = max(0, round($this->subtotal + $this->tax_amount - $this->discount, 2));
        }

        $this->save();
    }
}
