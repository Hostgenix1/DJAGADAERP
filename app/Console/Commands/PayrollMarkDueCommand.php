<?php

namespace App\Console\Commands;

use App\Services\PayrollService;
use Illuminate\Console\Command;

class PayrollMarkDueCommand extends Command
{
    protected $signature = 'payroll:mark-due {--date= : Treat this date as today (Y-m-d, for testing)}';

    protected $description = 'Mark approved payroll entries due (pay date arrived). Run daily at 00:00 via scheduler.';

    public function handle(): int
    {
        $count = PayrollService::markDue($this->option('date'));

        $this->info("Marked {$count} payroll entr".($count === 1 ? 'y' : 'ies').' as due.');

        return self::SUCCESS;
    }
}
