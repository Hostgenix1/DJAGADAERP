<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-payments');

        $totalPayments = Payment::count();
        $totalReceived = (float) Payment::where('type', 'customer')->sum('amount');
        $totalPaid = (float) Payment::where('type', 'supplier')->sum('amount');
        $netCash = $totalReceived - $totalPaid;
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $paymentByCurrency = Payment::leftJoin('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->selectRaw('currencies.code, currencies.symbol, payments.type, SUM(payments.amount) as total')
            ->groupBy('currencies.code', 'currencies.symbol', 'payments.type')
            ->get()
            ->toArray();

        return view('payments.index', compact('totalPayments', 'totalReceived', 'totalPaid', 'netCash', 'defaultCurrency', 'paymentByCurrency'));
    }

    public function datatable()
    {
        $this->authorize('view-payments');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->editColumn('type', fn (Payment $p) => '<span class="badge badge-'.($p->type==='customer'?'success':'warning').'">'.ucfirst($p->type).'</span>')
            ->editColumn('amount', fn (Payment $p) => number_format($p->amount, 2))
            ->editColumn('method', fn (Payment $p) => ucfirst($p->method))
            ->editColumn('paid_on', fn (Payment $p) => $p->paid_on?->format('d M Y'))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('party', fn (Payment $p) => $p->customer?->company_name ?? $p->supplier?->company_name ?? '-')
            ->addColumn('actions', fn (Payment $p) => view('payments.partials.actions', ['row' => $p])->render())
            ->rawColumns(['type', 'actions'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $this->authorize('create-payments');
        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $suppliers = \App\Models\Supplier::pluck('company_name', 'id');
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $methods = ['cash', 'bank', 'cheque', 'mobile', 'transfer'];

        $preselected = [
            'type' => $request->input('type', 'customer'),
            'supplier_id' => $request->input('supplier_id'),
            'customer_id' => $request->input('customer_id'),
            'bill_id' => $request->input('bill_id'),
        ];

        return view('payments.create', compact('customers', 'suppliers', 'currencies', 'methods', 'preselected'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-payments');

        $data = $request->validate([
            'type' => 'required|in:customer,supplier',
            'customer_id' => 'nullable|required_if:type,customer|exists:customers,id',
            'supplier_id' => 'nullable|required_if:type,supplier|exists:suppliers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'method' => 'required|in:cash,bank,cheque,mobile,transfer',
            'amount' => 'required|numeric|min:0.01',
            'paid_on' => 'required|date',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'allocations' => 'nullable|array',
            'allocations.*.invoice_id' => 'nullable|exists:invoices,id',
            'allocations.*.supplier_bill_id' => 'nullable|exists:supplier_bills,id',
            'allocations.*.amount' => 'required_with:allocations|numeric|min:0.01',
        ]);

        $allocations = $data['allocations'] ?? [];
        unset($data['allocations']);

        $allocations = array_map(function ($a) {
            if (empty($a['supplier_bill_id'])) {
                unset($a['supplier_bill_id']);
            }
            if (empty($a['invoice_id'])) {
                unset($a['invoice_id']);
            }
            return $a;
        }, $allocations);

        $this->service->createWithAllocation($data, $allocations);

        return redirect()->route('payments.index')->with('success', 'Payment recorded.');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view-payments');
        $payment->load(['customer', 'supplier', 'invoices', 'documents' => fn ($q) => $q->where('is_latest', true)]);

        return view('payments.show', compact('payment'));
    }

    public function outstanding()
    {
        $this->authorize('view-payments');

        $invoices = Invoice::with('customer')
            ->where('status', '!=', 'cancelled')
            ->whereRaw('total - paid_amount > 0')
            ->get()
            ->map(fn ($i) => [
                'id' => $i->id,
                'number' => $i->number,
                'customer' => $i->customer?->company_name,
                'total' => $i->total,
                'paid' => $i->paid_amount,
                'balance' => $i->balance,
                'due_date' => $i->due_date?->format('Y-m-d'),
                'overdue' => $i->due_date && $i->due_date->isPast(),
            ]);

        return view('payments.outstanding', compact('invoices'));
    }

    public function outstandingJson(Request $request)
    {
        $this->authorize('view-payments');

        if ($request->input('type') === 'supplier') {
            $bills = \App\Models\SupplierBill::with('supplier', 'currency')
                ->whereIn('status', ['confirmed', 'partial'])
                ->whereRaw('total - paid_amount > 0')
                ->get()
                ->map(fn ($b) => [
                    'id'      => $b->id,
                    'label'   => $b->number . ' — ' . ($b->supplier?->company_name ?? 'N/A') . ' (Bal: ' . ($b->currency?->symbol ?? '$') . number_format($b->total - $b->paid_amount, 2) . ')',
                    'balance' => (float) ($b->total - $b->paid_amount),
                ]);
            return response()->json($bills);
        }

        $invoices = \App\Models\Invoice::with('customer', 'currency')
            ->where('status', '!=', 'cancelled')
            ->whereRaw('total - paid_amount > 0')
            ->get()
            ->map(fn ($i) => [
                'id'      => $i->id,
                'label'   => $i->number . ' — ' . ($i->customer?->company_name ?? 'N/A') . ' (Bal: ' . ($i->currency?->symbol ?? '$') . number_format($i->total - $i->paid_amount, 2) . ')',
                'balance' => (float) ($i->total - $i->paid_amount),
            ]);
        return response()->json($invoices);
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete-payments');
        \App\Services\PaymentService::reverseAllocations($payment);
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted.');
    }
}
