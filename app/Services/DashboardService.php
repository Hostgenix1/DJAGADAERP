<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Quote;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'customers'       => Customer::count(),
            'products'        => Product::count(),
            'leads'           => \App\Models\Lead::count(),
            'active_leads'    => \App\Models\Lead::whereNotIn('status', ['won', 'lost'])->count(),
            'quotes'          => Quote::count(),
            'pending_quotes'  => Quote::where('status', 'sent')->count(),
            'invoices'        => Invoice::count(),
            'unpaid_invoices' => Invoice::where('status', '!=', 'cancelled')->whereRaw('total - paid_amount > 0')->count(),
            'total_revenue'   => (float) Payment::where('type', 'customer')->sum('amount'),
            'total_expenses'  => (float) Payment::where('type', 'supplier')->sum('amount'),
            'outstanding'     => (float) Invoice::where('status', '!=', 'cancelled')->sum(DB::raw('total - paid_amount')),
            'overdue'         => Invoice::where('status', '!=', 'cancelled')->where('due_date', '<', now())->whereRaw('total - paid_amount > 0')->count(),
        ];
    }

    public function getMonthlyRevenue(): array
    {
        $payments = Payment::where('type', 'customer')
            ->where('paid_on', '>=', now()->subMonths(12)->startOfMonth())
            ->selectRaw('MONTH(paid_on) as month, YEAR(paid_on) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = $payments->map(fn ($p) => date('M', mktime(0, 0, 0, $p->month, 1)).' '.$p->year);
        $data = $payments->pluck('total');

        return ['labels' => $labels->toArray(), 'data' => $data->toArray()];
    }

    public function getPipelineByStage(): array
    {
        $stages = \App\Models\Lead::selectRaw('status, count(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $labels = array_keys($stages);
        $data = array_values($stages);

        return ['labels' => $labels, 'data' => $data];
    }

    public function getRecentActivity(): array
    {
        return \Spatie\Activitylog\Models\Activity::with('causer')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($a) {
                $data = $a->toArray();
                $data['created_at'] = \Carbon\Carbon::parse($a->created_at, 'UTC')
                    ->setTimezone('Asia/Dubai')
                    ->format('d M Y h:i A');
                return $data;
            })
            ->toArray();
    }

    public function getTopCustomers(): array
    {
        return Customer::withSum(['invoices' => fn ($q) => $q->where('status', '!=', 'cancelled')], 'total')
            ->orderByDesc('invoices_sum_total')
            ->limit(5)
            ->get(['id', 'company_name'])
            ->toArray();
    }
}
