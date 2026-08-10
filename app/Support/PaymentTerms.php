<?php

namespace App\Support;

use App\Models\PaymentTerm;
use App\Services\SettingsService;

/**
 * Global payment-terms list, configurable from Settings.
 * Falls back to config/invoice.php when the table is empty.
 */
class PaymentTerms
{
    public static function all(): array
    {
        $terms = PaymentTerm::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        if (empty($terms)) {
            return config('invoice.payment_terms', []);
        }

        return $terms;
    }

    public static function defaultFor(string $docType): ?string
    {
        $key = 'default_pt_'.$docType;

        $value = app(SettingsService::class)->get($key);

        if ($value && in_array($value, self::all(), true)) {
            return $value;
        }

        return config('invoice.default_payment_terms.'.$docType, null);
    }

    public static function defaultsByType(): array
    {
        return [
            'commercial' => self::defaultFor('commercial'),
            'proforma' => self::defaultFor('proforma'),
            'quote' => self::defaultFor('quote'),
            'purchase_order' => self::defaultFor('purchase_order'),
            'supplier_bill' => self::defaultFor('supplier_bill'),
        ];
    }
}
