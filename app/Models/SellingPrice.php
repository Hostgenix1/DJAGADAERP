<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellingPrice extends Model
{
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'supplier_price_id', 'customer_id', 'product_id', 'packaging',
        'supplier_cost', 'margin_pct', 'margin_amount', 'selling_price',
        'currency_id', 'destination', 'incoterm', 'min_qty',
        'valid_until', 'status', 'approved_for_ai', 'notes',
    ];

    protected $casts = [
        'supplier_cost' => 'decimal:2',
        'margin_pct' => 'decimal:3',
        'margin_amount' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'min_qty' => 'decimal:2',
        'valid_until' => 'date',
        'approved_for_ai' => 'boolean',
    ];

    protected static function booted(): void
    {
        // A price whose validity has passed is never offered as approved.
        static::saving(function (self $price) {
            if ($price->valid_until && $price->valid_until->isPast() && $price->status === self::STATUS_APPROVED) {
                $price->status = self::STATUS_EXPIRED;
            }
        });
    }

    public function supplierPrice(): BelongsTo
    {
        return $this->belongsTo(SupplierPrice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /** Approved and still within validity (null valid_until = no expiry). */
    public function scopeCurrentlyApproved(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_APPROVED)
            ->where(fn ($w) => $w->whereNull('valid_until')->orWhere('valid_until', '>=', today()));
    }

    public function isCurrentlyValid(): bool
    {
        return $this->valid_until === null || $this->valid_until->gte(today());
    }

    /**
     * AI / customer-facing price resolution:
     * Customer-specific approved price -> general approved price -> null.
     * Expired prices are never returned. Only approved_for_ai prices are eligible.
     */
    public static function resolveForCustomer(?int $customerId, ?int $productId = null, ?int $minQty = null): ?self
    {
        $base = self::query()
            ->where('approved_for_ai', true)
            ->currentlyApproved()
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->when($minQty, fn ($q) => $q->where(fn ($w) => $w->whereNull('min_qty')->orWhere('min_qty', '<=', $minQty)))
            ->orderByDesc('id');

        if ($customerId) {
            $specific = (clone $base)->where('customer_id', $customerId)->first();
            if ($specific) {
                return $specific;
            }
        }

        return (clone $base)->whereNull('customer_id')->first();
    }
}
