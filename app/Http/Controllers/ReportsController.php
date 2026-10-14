<?php

namespace App\Http\Controllers;

use App\Exports\TableExport;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PayrollEntry;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SupplierBill;
use App\Services\StockService;
use App\Support\CurrencyHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function index()
    {
        $this->authorize('view-reports');

        return view('reports.index');
    }

    /* ------------------------------------------------------------------ */
    /* shared plumbing                                                     */
    /* ------------------------------------------------------------------ */

    protected function range(Request $request): array
    {
        $from = $request->date('from') ?: now()->startOfMonth();
        $to = $request->date('to') ?: now();

        return [$from->toDateString(), $to->toDateString()];
    }

    protected function baseCode(): string
    {
        return CurrencyHelper::baseCurrency()?->code ?? '';
    }

    /**
     * Render HTML view, or download xlsx / csv / pdf per ?export=.
     */
    protected function output(Request $request, string $view, string $title, array $headings, array $rows, array $totals, array $meta)
    {
        $export = $request->get('export');

        if ($export === 'xlsx' || $export === 'csv') {
            $lines = [];
            $lines[] = $headings;
            foreach ($rows as $row) {
                $lines[] = array_map(fn ($v) => trim(strip_tags((string) $v)), $row);
            }
            if ($totals) {
                $lines[] = array_map(fn ($v) => trim(strip_tags((string) $v)), $totals);
            }
            $filename = 'report-'.str_replace(' ', '-', strtolower($title)).'-'.now()->format('Ymd');

            if ($export === 'xlsx' && class_exists(Excel::class)) {
                return Excel::download(new TableExport($headings, array_slice($lines, 1), $title), $filename.'.xlsx');
            }

            return response()->streamDownload(function () use ($lines) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
                foreach ($lines as $line) {
                    fputcsv($out, $line);
                }
                fclose($out);
            }, $filename.'.csv', ['Content-Type' => 'text/csv']);
        }

        if ($export === 'pdf') {
            $svc = app(\App\Services\SettingsService::class);
            $company = [
                'name' => $svc->get('company_name'),
                'address' => $svc->get('company_address'),
                'city' => $svc->get('company_city'),
                'country' => $svc->get('company_country'),
                'phone' => $svc->get('company_phone'),
                'email' => $svc->get('company_email'),
                'trn' => $svc->get('company_trn'),
            ];

            $pdf = Pdf::loadView('reports.pdf.table', compact('title', 'headings', 'rows', 'totals', 'meta', 'company'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('report-'.str_replace(' ', '-', strtolower($title)).'-'.now()->format('Ymd').'.pdf');
        }

        return view($view, compact('title', 'headings', 'rows', 'totals', 'meta'));
    }

    /* ------------------------------------------------------------------ */
    /* sales                                                               */
    /* ------------------------------------------------------------------ */

    public function sales(Request $request)
    {
        $this->authorize('view-reports');

        [$from, $to] = $this->range($request);
        $base = $this->baseCode();

        $query = Invoice::with(['customer', 'currency'])
            ->where('status', '!=', 'cancelled')
            ->whereBetween('invoice_date', [$from, $to]);
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->integer('customer_id'));
        }

        $invoices = $query->orderByDesc('invoice_date')->get();
        $customers = Customer::orderBy('company_name')->pluck('company_name', 'id');

        $invoicedBase = $invoices->sum(fn ($i) => CurrencyHelper::convert($i->total, $i->currency_id));
        $paidBase = $invoices->sum(fn ($i) => CurrencyHelper::convert($i->paid_amount, $i->currency_id));
        $outstandingBase = $invoicedBase - $paidBase;

        $headings = ['Invoice', 'Date', 'Customer', 'Status', 'Currency', 'Total', 'Paid', 'Balance'];
        $rows = $invoices->map(fn (Invoice $i) => [
            e($i->number),
            $i->invoice_date?->format('d M Y'),
            e($i->customer?->company_name ?? '-'),
            ucfirst($i->status),
            $i->currency?->code,
            number_format((float) $i->total, 2),
            number_format((float) $i->paid_amount, 2),
            number_format((float) $i->total - (float) $i->paid_amount, 2),
        ])->all();

        $totals = ['', '', '', '', 'Base ('.$base.')', number_format($invoicedBase, 2), number_format($paidBase, 2), number_format($outstandingBase, 2)];
        $meta = [
            'From' => $from, 'To' => $to,
            'Invoiced (base)' => $base.' '.number_format($invoicedBase, 2),
            'Paid (base)' => $base.' '.number_format($paidBase, 2),
            'Outstanding (base)' => $base.' '.number_format($outstandingBase, 2),
        ];

        return $this->output($request, 'reports.sales', 'Sales Report', $headings, $rows, $totals, $meta);
    }

    /* ------------------------------------------------------------------ */
    /* financial (P&L by month)                                            */
    /* ------------------------------------------------------------------ */

    public function financial(Request $request)
    {
        $this->authorize('view-reports');

        [$from, $to] = $this->range($request);
        $base = $this->baseCode();

        $start = \Carbon\Carbon::parse($from)->startOfMonth();
        $end = \Carbon\Carbon::parse($to)->startOfMonth();

        $months = [];
        for ($m = $start->copy(); $m->lte($end); $m->addMonth()) {
            $months[] = $m->copy();
        }

        $rows = [];
        $sums = ['revenue' => 0.0, 'supplier' => 0.0, 'operating' => 0.0, 'salaries' => 0.0, 'gross' => 0.0, 'net' => 0.0];

        foreach ($months as $month) {
            $mStart = $month->copy()->startOfMonth()->toDateString();
            $mEnd = $month->copy()->endOfMonth()->toDateString();

            $revenue = Invoice::where('status', '!=', 'cancelled')->whereBetween('invoice_date', [$mStart, $mEnd])
                ->get(['total', 'currency_id'])->sum(fn ($i) => CurrencyHelper::convert($i->total, $i->currency_id));

            $supplierCost = SupplierBill::where('status', '!=', 'cancelled')->whereBetween('bill_date', [$mStart, $mEnd])
                ->get(['total', 'currency_id'])->sum(fn ($b) => CurrencyHelper::convert($b->total, $b->currency_id));

            $expenses = \App\Models\Expense::whereBetween('expense_date', [$mStart, $mEnd])->get(['amount', 'category', 'currency_id']);
            $operating = $expenses->where('category', '!=', 'payroll')->sum(fn ($e) => CurrencyHelper::convert($e->amount, $e->currency_id));
            $salaryExpenses = $expenses->where('category', 'payroll')->sum(fn ($e) => CurrencyHelper::convert($e->amount, $e->currency_id));

            $salaryAccrued = PayrollEntry::whereIn('status', [PayrollEntry::STATUS_APPROVED, PayrollEntry::STATUS_DUE])
                ->whereBetween('created_at', [$mStart.' 00:00:00', $mEnd.' 23:59:59'])
                ->get(['net_salary', 'currency_id'])->sum(fn ($p) => CurrencyHelper::convert($p->net_salary, $p->currency_id));

            $salaries = $salaryAccrued + $salaryExpenses;
            $gross = $revenue - $supplierCost;
            $net = $gross - $operating - $salaries;

            $sums['revenue'] += $revenue; $sums['supplier'] += $supplierCost;
            $sums['operating'] += $operating; $sums['salaries'] += $salaries;
            $sums['gross'] += $gross; $sums['net'] += $net;

            $rows[] = [
                $month->format('M Y'),
                number_format($revenue, 2), number_format($supplierCost, 2), number_format($operating, 2),
                number_format($salaries, 2), number_format($gross, 2), number_format($net, 2),
            ];
        }

        $headings = ['Month', 'Revenue', 'Supplier Costs', 'Operating Expenses', 'Salaries', 'Gross Profit', 'Net Profit'];
        $totals = ['TOTAL', number_format($sums['revenue'], 2), number_format($sums['supplier'], 2),
            number_format($sums['operating'], 2), number_format($sums['salaries'], 2),
            number_format($sums['gross'], 2), number_format($sums['net'], 2)];

        $meta = [
            'From' => $from, 'To' => $to, 'Currency' => $base,
            'Net Profit (range)' => $base.' '.number_format($sums['net'], 2),
        ];

        return $this->output($request, 'reports.financial', 'Financial Report', $headings, $rows, $totals, $meta);
    }

    /* ------------------------------------------------------------------ */
    /* customers                                                           */
    /* ------------------------------------------------------------------ */

    public function customers(Request $request)
    {
        $this->authorize('view-reports');

        [$from, $to] = $this->range($request);
        $base = $this->baseCode();

        $rows = [];
        $tInvoiced = $tPaid = 0.0;

        foreach (Customer::orderBy('company_name')->get() as $customer) {
            $invoices = Invoice::where('customer_id', $customer->id)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('invoice_date', [$from, $to])
                ->get(['total', 'paid_amount', 'currency_id']);

            if ($invoices->isEmpty()) {
                continue;
            }

            $invoiced = $invoices->sum(fn ($i) => CurrencyHelper::convert($i->total, $i->currency_id));
            $paid = $invoices->sum(fn ($i) => CurrencyHelper::convert($i->paid_amount, $i->currency_id));
            $outstanding = $invoiced - $paid;
            $tInvoiced += $invoiced; $tPaid += $paid;

            $rows[] = [
                e($customer->company_name),
                (string) $invoices->count(),
                number_format($invoiced, 2),
                number_format($paid, 2),
                number_format($outstanding, 2),
            ];
        }

        usort($rows, fn ($a, $b) => (float) str_replace(',', '', $b[2]) <=> (float) str_replace(',', '', $a[2]));

        $headings = ['Customer', 'Invoices', 'Invoiced', 'Paid', 'Outstanding'];
        $totals = ['TOTAL', '', number_format($tInvoiced, 2), number_format($tPaid, 2), number_format($tInvoiced - $tPaid, 2)];
        $meta = ['From' => $from, 'To' => $to, 'Currency' => $base.' (converted)'];

        return $this->output($request, 'reports.customers', 'Customer Report', $headings, $rows, $totals, $meta);
    }

    /* ------------------------------------------------------------------ */
    /* suppliers                                                           */
    /* ------------------------------------------------------------------ */

    public function suppliers(Request $request)
    {
        $this->authorize('view-reports');

        [$from, $to] = $this->range($request);
        $base = $this->baseCode();

        $rows = [];
        $tBilled = $tPaid = 0.0;

        foreach (\App\Models\Supplier::orderBy('company_name')->get() as $supplier) {
            $bills = SupplierBill::where('supplier_id', $supplier->id)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('bill_date', [$from, $to])
                ->get(['total', 'paid_amount', 'currency_id']);

            $poCount = PurchaseOrder::where('supplier_id', $supplier->id)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('po_date', [$from, $to])->count();

            if ($bills->isEmpty() && $poCount === 0) {
                continue;
            }

            $billed = $bills->sum(fn ($b) => CurrencyHelper::convert($b->total, $b->currency_id));
            $paid = $bills->sum(fn ($b) => CurrencyHelper::convert($b->paid_amount, $b->currency_id));
            $tBilled += $billed; $tPaid += $paid;

            $rows[] = [
                e($supplier->company_name),
                (string) $poCount,
                (string) $bills->count(),
                number_format($billed, 2),
                number_format($paid, 2),
                number_format($billed - $paid, 2),
            ];
        }

        $headings = ['Supplier', 'Purchase Orders', 'Bills', 'Billed', 'Paid', 'Balance'];
        $totals = ['TOTAL', '', '', number_format($tBilled, 2), number_format($tPaid, 2), number_format($tBilled - $tPaid, 2)];
        $meta = ['From' => $from, 'To' => $to, 'Currency' => $base.' (converted)'];

        return $this->output($request, 'reports.suppliers', 'Supplier Report', $headings, $rows, $totals, $meta);
    }

    /* ------------------------------------------------------------------ */
    /* inventory                                                           */
    /* ------------------------------------------------------------------ */

    public function inventory(Request $request)
    {
        $this->authorize('view-reports');

        $base = $this->baseCode();

        $products = Product::where('is_active', true)
            ->with(['currency'])
            ->withSum('stockMovements as stock_total', 'qty')
            ->orderBy('name')
            ->get();

        $rows = [];
        $tValue = 0.0;

        foreach ($products as $p) {
            $stock = (float) ($p->stock_total ?? 0);
            $value = CurrencyHelper::convert(max(0, $stock) * (float) $p->buy_price, $p->currency_id, CurrencyHelper::baseCurrencyId());
            $low = $p->reorder_level !== null && (float) $p->reorder_level > 0 && $stock <= (float) $p->reorder_level;
            $tValue += $value;

            $rows[] = [
                e($p->sku),
                e($p->name),
                number_format($stock, 2),
                $p->reorder_level !== null ? number_format((float) $p->reorder_level, 2) : '-',
                number_format((float) $p->buy_price, 2),
                number_format($value, 2),
                $low ? 'LOW' : '',
            ];
        }

        $headings = ['SKU', 'Product', 'On Hand', 'Reorder Level', 'Buy Price', 'Stock Value (base)', 'Alert'];
        $totals = ['', '', '', '', '', number_format($tValue, 2), ''];
        $meta = ['Currency' => $base, 'Stock Value' => $base.' '.number_format($tValue, 2)];

        return $this->output($request, 'reports.inventory', 'Inventory Report', $headings, $rows, $totals, $meta);
    }

    /* ------------------------------------------------------------------ */
    /* employees / payroll                                                 */
    /* ------------------------------------------------------------------ */

    public function employees(Request $request)
    {
        $this->authorize('view-reports');

        $period = $request->get('period', now()->format('Y-m'));
        if (! preg_match('/^\d{4}-\d{2}$/', (string) $period)) {
            $period = now()->format('Y-m');
        }
        $base = $this->baseCode();

        $entries = PayrollEntry::with(['employee', 'currency'])->where('period', $period)->orderBy('employee_id')->get();

        $rows = [];
        $tBase = $tAllow = $tDed = $tNet = 0.0;

        foreach ($entries as $e) {
            $tBase += (float) $e->gross_salary;
            $tAllow += (float) $e->allowances;
            $tDed += (float) $e->deductions;
            $tNet += CurrencyHelper::convert($e->net_salary, $e->currency_id);

            $rows[] = [
                e($e->employee?->name ?? '-'),
                $e->currency?->code,
                number_format((float) $e->gross_salary, 2),
                number_format((float) $e->allowances, 2),
                number_format((float) $e->deductions, 2),
                number_format((float) $e->net_salary, 2),
                ucfirst($e->status),
                $e->paid_on?->format('d M Y') ?? '-',
            ];
        }

        $headings = ['Employee', 'Cur', 'Base Salary', 'Allowances', 'Deductions', 'Net Salary', 'Status', 'Paid On'];
        $totals = ['TOTAL (' . $base . ' for net)', '', number_format($tBase, 2), number_format($tAllow, 2), number_format($tDed, 2), number_format($tNet, 2), '', ''];
        $meta = ['Period' => $period, 'Net total (base)' => $base.' '.number_format($tNet, 2)];

        return $this->output($request, 'reports.employees', 'Payroll Report', $headings, $rows, $totals, $meta);
    }
}
