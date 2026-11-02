<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\FollowUp;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PayrollEntry;
use App\Models\Product;
use App\Models\PurchaseRequest;
use App\Models\Quote;
use App\Models\Shipment;
use App\Models\Supplier;
use App\Models\SupplierBill;
use App\Support\CurrencyHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class DashboardService
{
    /**
     * Calendar events for the dashboard: follow-ups, invoice due dates,
     * shipment ETAs and purchase requests that are needed within the range.
     */
    public function getCalendarEvents(string $start, string $end): array
    {
        $events = [];

        FollowUp::whereBetween('due_date', [$start, $end])
            ->orderBy('due_date')
            ->get()
            ->each(function (FollowUp $followUp) use (&$events) {
                $events[] = [
                    'title' => 'Follow-up: '.ucfirst($followUp->type),
                    'start' => $followUp->due_date?->toDateString(),
                    'url' => route('follow_ups.edit', $followUp),
                    'color' => $followUp->completed_at ? '#28a745' : '#ffc107',
                ];
            });

        Invoice::whereIn('status', ['draft', 'sent', 'partial', 'overdue'])
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$start, $end])
            ->get()
            ->each(function (Invoice $invoice) use (&$events) {
                $events[] = [
                    'title' => 'Invoice '.$invoice->number.' due',
                    'start' => $invoice->due_date?->toDateString(),
                    'url' => route('invoices.show', $invoice),
                    'color' => '#dc3545',
                ];
            });

        Shipment::whereNotIn('status', ['delivered', 'cancelled'])
            ->whereNotNull('estimated_arrival')
            ->whereBetween('estimated_arrival', [$start, $end])
            ->get()
            ->each(function (Shipment $shipment) use (&$events) {
                $events[] = [
                    'title' => 'ETA '.$shipment->number,
                    'start' => $shipment->estimated_arrival?->toDateString(),
                    'url' => route('shipments.show', $shipment),
                    'color' => '#17a2b8',
                ];
            });

        PurchaseRequest::whereNotIn('status', ['converted', 'rejected'])
            ->whereNotNull('needed_by')
            ->whereBetween('needed_by', [$start, $end])
            ->get()
            ->each(function (PurchaseRequest $purchaseRequest) use (&$events) {
                $events[] = [
                    'title' => 'PR '.$purchaseRequest->number.' needed',
                    'start' => $purchaseRequest->needed_by?->toDateString(),
                    'url' => route('purchase_requests.show', $purchaseRequest),
                    'color' => '#6c757d',
                ];
            });

        return $events;
    }

    public function getStats(): array
    {
        return [
            'customers' => Customer::count(),
            'suppliers' => Supplier::count(),
            'products' => Product::count(),
            'leads' => Lead::count(),
            'active_leads' => Lead::whereNotIn('status', ['won', 'lost'])->count(),
            'quotes' => Quote::count(),
            'pending_quotes' => Quote::where('status', 'sent')->count(),
            'invoices' => Invoice::count(),
            'unpaid_invoices' => Invoice::where('status', '!=', 'cancelled')->whereRaw('total - paid_amount > 0')->count(),
            'partially_paid' => Invoice::where('status', 'partial')->count(),
            'fully_paid' => Invoice::where('status', 'paid')->count(),
            'overdue' => Invoice::where('status', '!=', 'cancelled')->where('due_date', '<', now())->whereRaw('total - paid_amount > 0')->count(),
            'total_revenue' => (float) Payment::where('type', 'customer')->sum('amount'),
            'total_expenses' => (float) Payment::where('type', 'supplier')->sum('amount'),
            'outstanding' => (float) Invoice::where('status', '!=', 'cancelled')->sum(DB::raw('total - paid_amount')),
            'monthly_sales' => (float) Invoice::where('status', '!=', 'cancelled')->whereMonth('invoice_date', now()->month)->whereYear('invoice_date', now()->year)->sum('total'),
            'monthly_revenue' => (float) Payment::where('type', 'customer')->whereMonth('paid_on', now()->month)->whereYear('paid_on', now()->year)->sum('amount'),
            'monthly_expenses' => (float) Payment::where('type', 'supplier')->whereMonth('paid_on', now()->month)->whereYear('paid_on', now()->year)->sum('amount'),
            'active_orders' => Order::whereNotIn('status', ['completed', 'cancelled'])->count(),
            'shipments_in_transit' => Shipment::where('status', 'in_transit')->count(),
            'low_stock' => StockService::lowStockProducts()->count(),
            'stock_value' => StockService::stockValue(),
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
        return SupplierBill::where('status', '!=', 'cancelled')
            ->whereRaw('total - paid_amount > 0')
            ->selectRaw('COUNT(*) as count, SUM(total - paid_amount) as total')
            ->first()
            ->toArray();
    }

    public function getPayablesByCurrency(): array
    {
        return SupplierBill::where('status', '!=', 'cancelled')
            ->whereRaw('total - paid_amount > 0')
            ->leftJoin('currencies', 'supplier_bills.currency_id', '=', 'currencies.id')
            ->selectRaw('COALESCE(currencies.code, "No currency") as code, COALESCE(currencies.symbol, "") as symbol, SUM(supplier_bills.total - supplier_bills.paid_amount) as total')
            ->groupByRaw('COALESCE(currencies.code, "No currency"), COALESCE(currencies.symbol, "")')
            ->get()
            ->toArray();
    }

    public function getRecentBills(): array
    {
        return SupplierBill::with('supplier', 'currency')
            ->latest()
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getMonthlyRevenue(): array
    {
        $payments = Payment::where('type', 'customer')
            ->where('paid_on', '>=', now()->subMonths(11)->startOfMonth())
            ->get(['paid_on', 'amount']);

        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = (float) $payments
                ->filter(fn (Payment $payment) => $payment->paid_on?->format('Y-m') === $month->format('Y-m'))
                ->sum('amount');
        }

        return ['labels' => $labels, 'data' => $data];
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
        $stages = Lead::selectRaw('status, count(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        return ['labels' => array_keys($stages), 'data' => array_values($stages)];
    }

    public function getTopSellingProducts(): array
    {
        return InvoiceItem::select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
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
        return Activity::with('causer')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($a) {
                $data = $a->toArray();
                $data['created_at'] = Carbon::parse($a->created_at, 'UTC')
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

        $baseId = CurrencyHelper::baseCurrencyId();

        $sumInBase = function ($amount, $currencyId) use ($baseId) {
            return CurrencyHelper::convert($amount, $currencyId, $baseId);
        };

        $dateCol = fn ($col) => $start
            ? fn ($q) => $q->whereBetween($col, [$start, $end])
            : fn ($q) => $q;

        $invoices = Invoice::where('status', '!=', 'cancelled');
        $dateCol('invoice_date')($invoices);
        $revenue = $invoices->get(['total', 'currency_id'])->sum(fn ($i) => $sumInBase($i->total, $i->currency_id));

        $bills = SupplierBill::where('status', '!=', 'cancelled');
        $dateCol('bill_date')($bills);
        $supplierCost = $bills->get(['total', 'currency_id'])->sum(fn ($b) => $sumInBase($b->total, $b->currency_id));

        $expenses = Expense::query();
        $dateCol('expense_date')($expenses);
        $expenses = $expenses->get(['amount', 'category', 'currency_id']);
        $operatingExpenses = $expenses->where('category', '!=', 'payroll')->sum(fn ($e) => $sumInBase($e->amount, $e->currency_id));
        $expensePayroll = $expenses->where('category', 'payroll')->sum(fn ($e) => $sumInBase($e->amount, $e->currency_id));

        // Salaries = approved/due payroll (accrued, unpaid) + payroll-category expenses (paid out).
        // Paid entries are excluded here because mark-paid auto-posts them as expenses — no double count.
        $payroll = PayrollEntry::whereIn('status', [
            PayrollEntry::STATUS_APPROVED,
            PayrollEntry::STATUS_DUE,
        ]);
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
            'base_currency' => CurrencyHelper::baseCurrency(),
        ];
    }

    public function getShipmentStats(): array
    {
        return [
            'in_transit' => Shipment::where('status', 'in_transit')->count(),
            'arrived' => Shipment::where('status', 'delivered')->count(),
            'preparing' => Shipment::where('status', 'preparing')->count(),
        ];
    }
}
