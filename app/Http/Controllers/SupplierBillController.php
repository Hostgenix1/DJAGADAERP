<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierBillRequest;
use App\Http\Requests\UpdateSupplierBillRequest;
use App\Models\PurchaseOrder;
use App\Models\SupplierBill;
use App\Services\SupplierBillService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierBillController extends Controller
{
    public function __construct(protected SupplierBillService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('view-supplier-bills');

        $supplier = $request->filled('supplier_id') ? \App\Models\Supplier::find($request->supplier_id) : null;

        $billQuery = SupplierBill::query();
        if ($supplier) {
            $billQuery->where('supplier_id', $supplier->id);
        }

        $totalBills = $billQuery->count();
        $totalAmount = (float) (clone $billQuery)->where('status', '!=', 'cancelled')->sum('total');
        $totalPaid = (float) (clone $billQuery)->where('status', '!=', 'cancelled')->sum('paid_amount');
        $totalOutstanding = $totalAmount - $totalPaid;
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $billByCurrency = (clone $billQuery)
            ->where('status', '!=', 'cancelled')
            ->leftJoin('currencies', 'supplier_bills.currency_id', '=', 'currencies.id')
            ->selectRaw('COALESCE(currencies.code, "No currency") as code, COALESCE(currencies.symbol, "-") as symbol, COUNT(*) as count, SUM(supplier_bills.total) as total, SUM(supplier_bills.paid_amount) as paid')
            ->groupByRaw('COALESCE(currencies.code, "No currency"), COALESCE(currencies.symbol, "-")')
            ->get()
            ->toArray();

        return view('supplier_bills.index', compact('totalBills', 'totalAmount', 'totalPaid', 'totalOutstanding', 'defaultCurrency', 'billByCurrency', 'supplier'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-supplier-bills');

        $query = $this->service->query();

        if ($request->filled('status')) {
            $allowedStatuses = ['draft', 'confirmed', 'paid', 'partial', 'cancelled'];
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('status', fn (SupplierBill $b) => '<span class="badge '.$b->status_badge.'">'.ucfirst($b->status).'</span>')
            ->editColumn('total', fn (SupplierBill $b) => number_format($b->total, 2))
            ->editColumn('balance', fn (SupplierBill $b) => '<span class="'.($b->balance > 0 ? 'text-danger' : 'text-success').'">'.number_format($b->balance, 2).'</span>')
            ->editColumn('bill_date', fn (SupplierBill $b) => $b->bill_date?->format('d M Y'))
            ->editColumn('due_date', fn (SupplierBill $b) => $b->due_date?->format('d M Y') ?? '-')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (SupplierBill $b) => view('supplier_bills.partials.actions', ['row' => $b])->render())
            ->rawColumns(['status', 'balance', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-supplier-bills');

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'company_name', 'currency_id', 'default_payment_term']);
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::with('tax:id,rate')->where('is_active', true)->get(['id', 'name', 'buy_price', 'unit', 'tax_id']);
        $taxes = \App\Models\Tax::where('is_active', true)->get();
        $units = \App\Support\Units::all();
        $paymentTerms = \App\Support\PaymentTerms::all();
        $defaultTax = \App\Models\Tax::sales()->where('is_default', true)->first();
        $rates = \App\Models\Currency::where('is_active', true)->pluck('rate', 'id');
        $defaultTerm = \App\Support\PaymentTerms::defaultFor('supplier_bill');

        return view('supplier_bills.create', compact('suppliers', 'currencies', 'products', 'taxes', 'units', 'paymentTerms', 'defaultTax', 'rates', 'defaultTerm'));
    }

    public function store(StoreSupplierBillRequest $request)
    {
        $this->authorize('create-supplier-bills');

        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        if ($data['payment_terms'] === 'Custom' && $request->filled('payment_terms_custom')) {
            $data['payment_terms'] = $request->input('payment_terms_custom');
        }

        $bill = $this->service->createWithItems($data, $items);

        return redirect()->route('supplier_bills.show', $bill)->with('success', 'Supplier bill created.');
    }

    public function show(SupplierBill $supplierBill)
    {
        $this->authorize('view-supplier-bills');
        $supplierBill->load(['supplier', 'currency', 'items.product', 'payments', 'purchaseOrder']);
        $supplierBill->load(['documents' => fn ($q) => $q->where('is_latest', true)]);

        return view('supplier_bills.show', compact('supplierBill'));
    }

    public function edit(SupplierBill $supplierBill)
    {
        $this->authorize('update-supplier-bills');
        $supplierBill->load('items');

        if (!in_array($supplierBill->status, ['draft'])) {
            return back()->with('error', 'Only draft supplier bills can be edited.');
        }

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'company_name', 'currency_id', 'default_payment_term']);
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::with('tax:id,rate')->where('is_active', true)->get(['id', 'name', 'buy_price', 'unit', 'tax_id']);
        $taxes = \App\Models\Tax::where('is_active', true)->get();
        $units = \App\Support\Units::all();
        $paymentTerms = \App\Support\PaymentTerms::all();
        $defaultTax = \App\Models\Tax::sales()->where('is_default', true)->first();
        $rates = \App\Models\Currency::where('is_active', true)->pluck('rate', 'id');
        $defaultTerm = \App\Support\PaymentTerms::defaultFor('supplier_bill');

        return view('supplier_bills.edit', compact('supplierBill', 'suppliers', 'currencies', 'products', 'taxes', 'units', 'paymentTerms', 'defaultTax', 'rates', 'defaultTerm'));
    }

    public function update(UpdateSupplierBillRequest $request, SupplierBill $supplierBill)
    {
        $this->authorize('update-supplier-bills');

        if (!in_array($supplierBill->status, ['draft'])) {
            return back()->with('error', 'Only draft supplier bills can be edited.');
        }

        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        if ($data['payment_terms'] === 'Custom' && $request->filled('payment_terms_custom')) {
            $data['payment_terms'] = $request->input('payment_terms_custom');
        }

        $this->service->updateWithItems($supplierBill, $data, $items);

        return redirect()->route('supplier_bills.show', $supplierBill)->with('success', 'Supplier bill updated.');
    }

    public function status(SupplierBill $supplierBill, Request $request)
    {
        $this->authorize('update-supplier-bills');

        $request->validate([
            'status' => 'required|in:draft,confirmed,paid,partial,cancelled',
        ]);

        $supplierBill->update(['status' => $request->status]);

        return back()->with('success', 'Status updated to '.ucfirst($request->status).'.');
    }

    public function convertFromPo(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('create-supplier-bills');

        try {
            $bill = $this->service->convertFromPo($purchaseOrder);
            return redirect()->route('supplier_bills.show', $bill)
                ->with('success', 'Supplier bill created from purchase order.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pdf(SupplierBill $supplierBill)
    {
        $this->authorize('view-supplier-bills');
        $supplierBill->load(['supplier', 'currency', 'items.product', 'purchaseOrder']);

        $svc = app(\App\Services\SettingsService::class);
        $logoPath = $svc->get('company_logo');
        $logoBase64 = null;
        if ($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
            $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($logoPath);
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $mime = match($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                default => 'image/png',
            };
            $logoBase64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($fullPath));
        }

        $sigPath = $svc->get('company_signature');
        $sigBase64 = null;
        if ($sigPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($sigPath)) {
            $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($sigPath);
            $ext = strtolower(pathinfo($sigPath, PATHINFO_EXTENSION));
            $mime = match($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                default => 'image/png',
            };
            $sigBase64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($fullPath));
        }

        $company = [
            'name'      => $svc->get('company_name'),
            'address'   => $svc->get('company_address'),
            'city'      => $svc->get('company_city'),
            'country'   => $svc->get('company_country'),
            'email'     => $svc->get('company_email'),
            'phone'     => $svc->get('company_phone'),
            'tax_id'    => $svc->get('company_tax_id'),
            'registration' => $svc->get('company_registration'),
            'footer'    => $svc->get('company_footer_text'),
            'show_logo' => $svc->get('show_logo_on_docs'),
            'logo_url'  => $logoBase64,
            'signature_url' => $sigBase64,
            'trade_license' => $svc->get('company_trade_license'),
            'trn'           => $svc->get('company_trn'),
            'free_zone'     => $svc->get('company_free_zone'),
            'entity_type'   => $svc->get('company_entity_type'),
            'bank_name'    => $svc->get('company_bank_name'),
            'bank_account' => $svc->get('company_bank_account'),
            'bank_number'  => '',
            'bank_iban'    => $svc->get('company_bank_iban'),
            'bank_swift'   => $svc->get('company_bank_swift'),
            'bank_address' => $svc->get('company_bank_address'),
        ];

        $html = view('supplier_bills.pdf', ['bill' => $supplierBill, 'company' => $company])->render();

        $pdf = Pdf::loadHtml($html)
            ->setPaper('a4')
            ->set_option('isHtml5ParserEnabled', true)
            ->set_option('isRemoteEnabled', true);

        return $pdf->download(str_replace('/', '-', $supplierBill->number).'.pdf');
    }

    public function destroy(SupplierBill $supplierBill)
    {
        $this->authorize('delete-supplier-bills');

        if (!in_array($supplierBill->status, ['draft', 'cancelled'])) {
            return back()->with('error', 'Only draft or cancelled bills can be deleted.');
        }

        $supplierBill->items()->delete();
        $supplierBill->delete();

        return redirect()->route('supplier_bills.index')->with('success', 'Supplier bill deleted.');
    }
}
