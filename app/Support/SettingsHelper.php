<?php

namespace App\Support;

use App\Services\SettingsService;

class SettingsHelper
{
    public static function wordsLang(): string
    {
        return app(SettingsService::class)->get('amount_in_words_lang', 'en');
    }

    /**
     * Format an amount per settings: european (1 234,56) or international (1,234.56).
     */
    public static function formatMoney(float|int|string|null $amount, int $decimals = 2): string
    {
        $amount = (float) ($amount ?? 0);
        $format = app(SettingsService::class)->get('number_format', 'european');

        if ($format === 'international') {
            return number_format($amount, $decimals);
        }

        return number_format($amount, $decimals, ',', ' ');
    }
}
