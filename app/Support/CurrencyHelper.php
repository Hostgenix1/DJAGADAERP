<?php

namespace App\Support;

use App\Models\Currency;
use App\Services\SettingsService;

class CurrencyHelper
{
    /**
     * Base (default) currency. Product prices are stored in this currency.
     * Semantics: rate = units of this currency per 1 unit of base currency.
     */
    public static function baseCurrency(): ?Currency
    {
        $currency = Currency::where('is_default', true)->first();

        if (!$currency) {
            $id = app(SettingsService::class)->get('default_currency_id');
            $currency = $id ? Currency::find($id) : null;
        }

        return $currency;
    }

    public static function baseCurrencyId(): ?int
    {
        return self::baseCurrency()?->id;
    }

    /** Rate of a currency vs base. Base currency (or null) = 1. */
    public static function rateOf(?int $currencyId): float
    {
        if (!$currencyId) {
            return 1.0;
        }

        $currency = Currency::find($currencyId);

        if (!$currency || !$currency->rate || (float) $currency->rate <= 0) {
            return 1.0;
        }

        return (float) $currency->rate;
    }

    /**
     * Convert an amount from source currency to target currency.
     * Source defaults to base currency, target defaults to base currency.
     */
    public static function convert(float|int|string|null $amount, ?int $fromCurrencyId = null, ?int $toCurrencyId = null): float
    {
        $amount = (float) ($amount ?? 0);

        if ($fromCurrencyId && $fromCurrencyId === $toCurrencyId) {
            return $amount;
        }

        $fromRate = self::rateOf($fromCurrencyId);
        $toRate = self::rateOf($toCurrencyId);

        return round($amount / $fromRate * $toRate, 2);
    }

    /** Base → target currency. */
    public static function fromBase(float|int|string|null $amount, ?int $toCurrencyId = null): float
    {
        return self::convert($amount, null, $toCurrencyId);
    }

    /** Format a base-currency amount with the base currency code (e.g. "12.50 USD"). */
    public static function formatBase(float|int|string|null $amount, int $decimals = 2): string
    {
        $base = self::baseCurrency();
        $code = $base?->code ?: '';

        return number_format((float) ($amount ?? 0), $decimals) . ($code ? ' ' . $code : '');
    }

    /**
     * Set a currency as the new base (default) and rescale all rates.
     * newRate(i) = rate(i) / newBaseRate
     */
    public static function setDefault(Currency $currency): void
    {
        $oldBaseRate = (float) ($currency->rate ?: 1);

        if ($oldBaseRate <= 0) {
            $oldBaseRate = 1;
        }

        Currency::query()->update(['is_default' => false]);

        foreach (Currency::all() as $c) {
            if ($c->id === $currency->id) {
                $c->update(['rate' => 1, 'is_default' => true]);
            } else {
                $rate = $c->rate ? (float) $c->rate : 0;
                $c->update(['rate' => round($rate / $oldBaseRate, 4)]);
            }
        }

        app(SettingsService::class)->set('default_currency_id', (string) $currency->id);
    }
}
