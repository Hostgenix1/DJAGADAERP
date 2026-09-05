<?php

namespace App\Console\Commands;

use App\Models\SellingPrice;
use Illuminate\Console\Command;

class PricingExpireCommand extends Command
{
    protected $signature = 'pricing:expire';

    protected $description = 'Mark approved selling prices past their Valid Until date as expired (housekeeping; reads also apply lazy expiry)';

    public function handle(): int
    {
        $count = SellingPrice::where('status', SellingPrice::STATUS_APPROVED)
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', '<', today())
            ->update(['status' => SellingPrice::STATUS_EXPIRED]);

        $this->info("Expired {$count} selling price(s).");

        return self::SUCCESS;
    }
}
