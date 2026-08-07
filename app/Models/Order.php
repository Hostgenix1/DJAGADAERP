<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'customer_id', 'currency_id', 'order_date', 'expected_delivery',
        'status', 'subtotal', 'tax_amount', 'discount', 'total', 'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function currency(): BelongsTo { return $this->belongsTo(Currency::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }

    public function documents()
    {
        return $this->morphMany(\App\Models\Document::class, 'documentable');
    }

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

    public static function nextNumber(): string
    {
        $last = self::withTrashed()->orderBy('id', 'desc')->value('number');
        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }
        return 'SO-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'confirmed' => 'badge-info',
            'processing' => 'badge-warning',
            'completed' => 'badge-success',
            'cancelled' => 'badge-dark',
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