<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'type', 'customer_id', 'currency_id', 'bank_account_id', 'quote_id',
        'invoice_date', 'due_date', 'status', 'subtotal', 'tax_amount',
        'discount', 'total', 'paid_amount', 'notes', 'terms',
        'sign_path', 'signed_by', 'signed_at',
        'reference_no', 'payment_terms', 'delivery_terms',
        'port_of_loading', 'port_of_discharge', 'goods_origin',
        'offer_valid', 'vat_mode', 'vat_rate',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'signed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'offer_valid' => 'integer',
        'vat_rate' => 'decimal:3',
    ];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
    public function quote(): BelongsTo { return $this->belongsTo(Quote::class); }
    public function items(): HasMany { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->belongsToMany(Payment::class, 'invoice_payments')->withPivot('amount'); }
    public function bankAccount(): BelongsTo { return $this->belongsTo(\App\Models\CompanyBankAccount::class, 'bank_account_id'); }

    public function documents() { return $this->morphMany(Document::class, 'documentable'); }

    public function shipments()
    {
        return $this->hasMany(\App\Models\Shipment::class);
    }

    public function follow_ups()
    {
        return $this->morphMany(\App\Models\FollowUp::class, 'followable');
    }

    public function communications()
    {
        return $this->morphMany(\App\Models\Communication::class, 'communicable');
    }

    public static function nextNumber(string $type = 'commercial'): string
    {
        $prefix = match($type) {
            'proforma' => 'PI',
            'credit_note' => 'CN',
            'packing_list' => 'PL',
            'delivery_note' => 'DN',
            default => 'INV',
        };

        $year = now()->format('Y');
        $last = self::where('type', $type)
            ->where('number', 'like', $prefix.'/'.$year.'/%')
            ->withTrashed()
            ->orderByDesc('id')
            ->value('number');

        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }

        return $prefix.'/'.$year.'/'.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'sent' => 'badge-info',
            'paid' => 'badge-success',
            'partial' => 'badge-warning',
            'overdue' => 'badge-danger',
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
        $items = $this->items->loadMissing('product');

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

    public function getTypesAttribute(): array
    {
        return ['commercial', 'proforma', 'credit_note', 'packing_list', 'delivery_note'];
    }
}
