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
            'customers'         => Customer::count(),
            'suppliers'         => \App\Models\Supplier::count(),
            'products'          => Product::count(),
            'leads'             => \App\Models\Lead::count(),
            'active_leads'      => \App\Models\Lead::whereNotIn('status', ['won', 'lost'])->count(),
            'quotes'            => Quote::count(),
            'pending_quotes'    => Quote::where('status', 'sent')->count(),
            'invoices'          => Invoice::count(),
            'unpaid_invoices'   => Invoice::where('status', '!=', 'cancelled')->whereRaw('total - paid_amount > 0')->count(),
            'partially_paid'    => Invoice::where('status', 'partial')->count(),
            'fully_paid'        => Invoice::where('status', 'paid')->count(),
            'overdue'           => Invoice::where('status', '!=', 'cancelled')->where('due_date', '<', now())->whereRaw('total - paid_amount > 0')->count(),
            'total_revenue'     => (float) Payment::where('type', 'customer')->sum('amount'),
            'total_expenses'    => (float) Payment::where('type', 'supplier')->sum('amount'),
            'outstanding'       => (float) Invoice::where('status', '!=', 'cancelled')->sum(DB::raw('total - paid_amount')),
            'monthly_sales'     => (float) Invoice::where('status', '!=', 'cancelled')->whereMonth('invoice_date', now()->month)->whereYear('invoice_date', now()->year)->sum('total'),
            'monthly_revenue'   => (float) Payment::where('type', 'customer')->whereMonth('paid_on', now()->month)->whereYear('paid_on', now()->year)->sum('amount'),
            'monthly_expenses'  => (float) Payment::where('type', 'supplier')->whereMonth('paid_on', now()->month)->whereYear('paid_on', now()->year)->sum('amount'),
            'active_orders'     => \App\Models\Order::whereNotIn('status', ['completed', 'cancelled'])->count(),
            'shipments_in_transit' => \App\Models\Shipment::where('status', 'in_transit')->count(),
        ];
    }

    public function getRevenueByCurrency(): array
    {
        return Payment::where('type', 'customer')
            ->join('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->selectRaw('currencies.code, currencies.symbol, SUM(payments.amount) as total')
            ->groupBy('currencies.code', 'currencies.symbol')
            ->get()
            ->toArray();
    }

    public function getOutstandingByCurrency(): array
    {
        return Invoice::where('status', '!=', 'cancelled')
            ->whereRaw('total - paid_amount > 0')
            ->leftJoin('currencies', 'invoices.currency_id', '=', 'currencies.id')
            ->selectRaw('COALESCE(currencies.code, "No currency") as code, COALESCE(currencies.symbol, "") as symbol, SUM(invoices.total - invoices.paid_amount) as total')
            ->groupByRaw('COALESCE(currencies.code, "No currency"), COALESCE(currencies.symbol, "")')
            ->get()
            ->toArray();
    }

    public function getPayablesSummary(): array
    {
        return \App\Models\SupplierBill::where('status', '!=', 'cancelled')
            ->whereRaw('total - paid_amount > 0')
            ->selectRaw('COUNT(*) as count, SUM(total - paid_amount) as total')
            ->first()
            ->toArray();
    }

    public function getPayablesByCurrency(): array
    {
        return \App\Models\SupplierBill::where('status', '!=', 'cancelled')
            ->whereRaw('total - paid_amount > 0')
            ->leftJoin('currencies', 'supplier_bills.currency_id', '=', 'currencies.id')
            ->selectRaw('COALESCE(currencies.code, "No currency") as code, COALESCE(currencies.symbol, "") as symbol, SUM(supplier_bills.total - supplier_bills.paid_amount) as total')
            ->groupByRaw('COALESCE(currencies.code, "No currency"), COALESCE(currencies.symbol, "")')
            ->get()
            ->toArray();
    }

    public function getRecentBills(): array
    {
        return \App\Models\SupplierBill::with('supplier', 'currency')
            ->latest()
            ->limit(5)
            ->get()
            ->toArray();
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

    public function getCashFlow(): array
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push($date->format('M Y'));
        }

        $revenue = [];
        $expenses = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue[] = (float) Payment::where('type', 'customer')
                ->whereMonth('paid_on', $date->month)
                ->whereYear('paid_on', $date->year)
                ->sum('amount');
            $expenses[] = (float) Payment::where('type', 'supplier')
                ->whereMonth('paid_on', $date->month)
                ->whereYear('paid_on', $date->year)
                ->sum('amount');
        }

        return [
            'labels' => $months->toArray(),
            'revenue' => $revenue,
            'expenses' => $expenses,
        ];
    }

    public function getPipelineByStage(): array
    {
        $stages = \App\Models\Lead::selectRaw('status, count(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        return ['labels' => array_keys($stages), 'data' => array_values($stages)];
    }

    public function getTopSellingProducts(): array
    {
        return \App\Models\InvoiceItem::select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->get(['products.name', 'total_qty', 'total_revenue'])
            ->toArray();
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

    /**
     * Resolve [start, end] dates from a period key: month / quarter / year / all.
     */
    public function periodRange(string $period): array
    {
        return match ($period) {
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'quarter' => [now()->startOfQuarter(), now()->endOfQuarter()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            default => [null, null],
        };
    }

    /**
     * Profit & Loss summary for a given period, all converted to the base (default) currency.
     */
    public function getPnl(string $period = 'month'): array
    {
        [$start, $end] = $this->periodRange($period);

        $baseId = \App\Support\CurrencyHelper::baseCurrencyId();

        $sumInBase = function ($amount, $currencyId) use ($baseId) {
            return \App\Support\CurrencyHelper::convert($amount, $currencyId, $baseId);
        };

        $dateCol = fn ($col) => $start
            ? fn ($q) => $q->whereBetween($col, [$start, $end])
            : fn ($q) => $q;

        $invoices = Invoice::where('status', '!=', 'cancelled');
        $dateCol('invoice_date')($invoices);
        $revenue = $invoices->get(['total', 'currency_id'])->sum(fn ($i) => $sumInBase($i->total, $i->currency_id));

        $bills = \App\Models\SupplierBill::where('status', '!=', 'cancelled');
        $dateCol('bill_date')($bills);
        $supplierCost = $bills->get(['total', 'currency_id'])->sum(fn ($b) => $sumInBase($b->total, $b->currency_id));

        $expenses = \App\Models\Expense::query();
        $dateCol('expense_date')($expenses);
        $expenses = $expenses->get(['amount', 'category', 'currency_id']);
        $operatingExpenses = $expenses->where('category', '!=', 'payroll')->sum(fn ($e) => $sumInBase($e->amount, $e->currency_id));
        $expensePayroll = $expenses->where('category', 'payroll')->sum(fn ($e) => $sumInBase($e->amount, $e->currency_id));

        $payroll = \App\Models\PayrollEntry::where('status', '!=', 'draft');
        $dateCol('created_at')($payroll);
        $salaryCost = $payroll->get(['net_salary', 'currency_id'])->sum(fn ($p) => $sumInBase($p->net_salary, $p->currency_id));

        $salaries = $salaryCost + $expensePayroll;

        $grossProfit = $revenue - $supplierCost;
        $netProfit = $grossProfit - $operatingExpenses - $salaries;

        return [
            'period' => $period,
            'revenue' => round($revenue, 2),
            'supplier_cost' => round($supplierCost, 2),
            'operating_expenses' => round($operatingExpenses, 2),
            'salaries' => round($salaries, 2),
            'gross_profit' => round($grossProfit, 2),
            'net_profit' => round($netProfit, 2),
            'base_currency' => \App\Support\CurrencyHelper::baseCurrency(),
        ];
    }

    public function getShipmentStats(): array
    {
        return [
            'in_transit' => \App\Models\Shipment::where('status', 'in_transit')->count(),
            'arrived' => \App\Models\Shipment::where('status', 'delivered')->count(),
            'preparing' => \App\Models\Shipment::where('status', 'preparing')->count(),
        ];
    }
}
