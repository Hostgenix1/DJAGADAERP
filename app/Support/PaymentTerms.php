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

    /**
     * Parse percentage milestones out of a payment term, e.g.
     * "30% advance + 70% before shipment" -> [30%, 70%] with amounts.
     */
    public static function milestones(string $term, float $total = 0): array
    {
        $milestones = [];

        foreach (preg_split('/[+,;]/', $term) as $part) {
            $part = trim($part);
            if (! $part) {
                continue;
            }

            if (preg_match('/(\d+(?:\.\d+)?)\s*%/', $part, $m)) {
                $percent = (float) $m[1];
                $milestones[] = [
                    'label' => $part,
                    'percent' => $percent,
                    'amount' => round($total * $percent / 100, 2),
                ];
            }
        }

        return $milestones;
    }

    /** True if the term contains percentage milestones (e.g. "30% + 70%"). */
    public static function hasMilestones(?string $term): bool
    {
        return $term !== null && $term !== '' && (bool) preg_match('/\d+\s*%/', $term);
    }

    /**
     * Amount due right now per the payment-term schedule.
     * Returns the first milestone (stage) not yet fully covered by paid
     * amount, with the remaining part of that milestone. null when the
     * term has no percentage milestones or everything is covered.
     */
    public static function dueNow(?string $term, float $total, float $paid): ?array
    {
        if (! self::hasMilestones($term)) {
            return null;
        }

        $cumulative = 0.0;
        foreach (self::milestones((string) $term, $total) as $ms) {
            $cumulative += $ms['amount'];
            if ($paid < $cumulative - 0.005) {
                return [
                    'amount' => round($cumulative - $paid, 2),
                    'label' => $ms['label'],
                ];
            }
        }

        return null;
    }
}
