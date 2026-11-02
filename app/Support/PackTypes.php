<?php

namespace App\Support;

use App\Models\PackType;

/**
 * Global pack-types list, configurable from Settings.
 * Falls back to the historical defaults when the table is empty.
 */
class PackTypes
{
    public const DEFAULTS = ['carton', 'box', 'unit', 'pallet'];

    public static function all(): array
    {
        $types = PackType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        if (empty($types)) {
            return self::DEFAULTS;
        }

        return $types;
    }
}
