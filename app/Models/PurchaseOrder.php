<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'supplier_id', 'currency_id', 'po_date', 'expected_delivery',
        'status', 'payment_terms', 'delivery_terms',
        'port_of_loading', 'port_of_discharge', 'goods_origin',
        'reference_no', 'vat_mode', 'vat_rate',
        'subtotal', 'tax_amount', 'discount', 'total', 'notes',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    protected $casts = [
        'po_date' => 'date',
        'expected_delivery' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'vat_rate' => 'decimal:3',
    ];

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
    public function items(): HasMany { return $this->hasMany(PurchaseOrderItem::class); }
    public function supplierBills(): HasMany { return $this->hasMany(SupplierBill::class); }

    public function documents() { return $this->morphMany(Document::class, 'documentable'); }

    public static function nextNumber(): string
    {
        $year = now()->format('Y');
        $last = self::where('number', 'like', 'PO/'.$year.'/%')
            ->withTrashed()
            ->orderByDesc('id')
            ->value('number');

        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }

        return 'PO/'.$year.'/'.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'confirmed' => 'badge-info',
            'received' => 'badge-primary',
            'billed' => 'badge-success',
            'cancelled' => 'badge-dark',
            default => 'badge-light',
        };
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
