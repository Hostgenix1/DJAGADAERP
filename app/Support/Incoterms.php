<?php

namespace App\Support;

use App\Services\SettingsService;

class Incoterms
{
    public static function all(): array
    {
        $setting = app(SettingsService::class)->get('incoterms_list', '');
        if (! empty($setting)) {
            return array_values(array_filter(array_map('trim', explode(',', $setting))));
        }

        return config('invoice.incoterms', ['EXW', 'FOB', 'CFR', 'CIF', 'DAP', 'DDP', 'Other']);
    }
}
