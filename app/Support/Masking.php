<?php

namespace App\Support;

class Masking
{
    /**
     * Mask an IBAN for payslips: first 8 chars + last 4 visible, middle masked.
     * AE970330000019101860955 -> AE97 0330 XXXX XXXX XXX 0955
     */
    public static function iban(?string $iban): ?string
    {
        if (! $iban) {
            return null;
        }

        $clean = strtoupper(preg_replace('/\s+/', '', $iban));
        $len = strlen($clean);
        if ($len < 12) {
            return $clean; // too short to mask meaningfully
        }

        $visibleStart = 8;
        $visibleEnd = 4;
        $middleLen = $len - $visibleStart - $visibleEnd;

        // Groups align with the masked middle (preview style: AE07 0331 XXXX XXXX XXX 3456)
        $groups = [
            substr($clean, 0, 4),
            substr($clean, 4, 4),
        ];
        foreach (str_split(str_repeat('X', $middleLen), 4) as $chunk) {
            $groups[] = $chunk;
        }
        $groups[] = substr($clean, -$visibleEnd);

        return implode(' ', array_filter($groups));
    }

    /**
     * Mask an Emirates ID: 784-1990-1234567-1 -> 784-XXXX-XXXXXXX-1
     * Generic fallback: keep last 4 characters visible.
     */
    public static function emiratesId(?string $id): ?string
    {
        if (! $id) {
            return null;
        }

        $parts = explode('-', $id);
        if (count($parts) === 4) {
            return $parts[0].'-'.str_repeat('X', strlen($parts[1])).'-'.str_repeat('X', strlen($parts[2])).'-'.$parts[3];
        }

        $len = strlen($id);
        if ($len <= 4) {
            return $id;
        }

        return str_repeat('X', $len - 4).substr($id, -4);
    }

    /**
     * Amount in words for the payslip ("... dirhams only").
     */
    public static function amountInWords(?float $amount, ?string $currencyCode): ?string
    {
        if ($amount === null) {
            return null;
        }

        try {
            $words = NumberToWords::toWords($amount, 'en');
            $unit = match (strtoupper((string) $currencyCode)) {
                'AED' => 'dirhams',
                'USD' => 'dollars',
                'EUR' => 'euros',
                'GBP' => 'pounds',
                'SAR' => 'riyals',
                default => strtolower((string) $currencyCode),
            };

            // Whole amounts: "<Words> dirhams only". With fils: "<Words> and NN Cents dirhams"
            if (fmod(abs($amount), 1.0) === 0.0) {
                return trim($words) . ' ' . $unit . ' only';
            }

            return $words . ' ' . $unit;
        } catch (\Throwable $e) {
            return null; // optional field: leave empty when it cannot be generated
        }
    }
}
