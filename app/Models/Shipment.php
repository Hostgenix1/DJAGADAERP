<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number', 'order_id', 'invoice_id', 'customer_id', 'carrier',
        'tracking_number', 'shipping_method', 'origin', 'destination',
        'status', 'shipped_at', 'estimated_arrival', 'delivered_at', 'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'estimated_arrival' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }

    public static function nextNumber(): string
    {
        $last = self::orderBy('id', 'desc')->value('number');
        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        }
        return 'SHP-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'preparing' => 'badge-secondary',
            'in_transit' => 'badge-info',
            'customs' => 'badge-warning',
            'delivered' => 'badge-success',
            'cancelled' => 'badge-dark',
            default => 'badge-light',
        };
    }
}