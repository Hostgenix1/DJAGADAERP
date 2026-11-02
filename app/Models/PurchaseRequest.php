<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'requested_by', 'supplier_id', 'currency_id', 'department',
        'needed_by', 'priority', 'status', 'justification', 'notes',
        'estimated_total', 'approved_by', 'approved_at', 'purchase_order_id',
    ];

    protected $attributes = [
        'status' => 'draft',
        'priority' => 'normal',
    ];

    protected $casts = [
        'needed_by' => 'date',
        'approved_at' => 'datetime',
        'estimated_total' => 'decimal:2',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public static function nextNumber(): string
    {
        $year = now()->format('Y');
        $last = self::withTrashed()
            ->where('number', 'like', 'PR/'.$year.'/%')
            ->orderByDesc('id')
            ->value('number');

        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }

        return 'PR/'.$year.'/'.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'badge-secondary',
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'converted' => 'badge-primary',
            default => 'badge-light',
        };
    }

    public function recalculate(): void
    {
        $this->estimated_total = round((float) $this->items()->sum('line_total'), 2);
        $this->save();
    }
}
