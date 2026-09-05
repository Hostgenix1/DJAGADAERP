<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Services\PurchaseOrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-purchase-orders');

        $totalPos = PurchaseOrder::count();
        $totalAmount = (float) PurchaseOrder::where('status', '!=', 'cancelled')->sum('total');
        $pending = (float) PurchaseOrder::whereIn('status', ['draft', 'confirmed'])->sum('total');
        $received = (float) PurchaseOrder::where('status', 'received')->sum('total');
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $poByCurrency = PurchaseOrder::where('status', '!=', 'cancelled')
            ->leftJoin('currencies', 'purchase_orders.currency_id', '=', 'currencies.id')
            ->selectRaw('currencies.code, currencies.symbol, COUNT(*) as count, SUM(purchase_orders.total) as total')
            ->groupBy('currencies.code', 'currencies.symbol')
            ->get()
            ->toArray();

        return view('purchase_orders.index', compact('totalPos', 'totalAmount', 'pending', 'received', 'defaultCurrency', 'poByCurrency'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-purchase-orders');

        $query = $this->service->query();

        if ($request->filled('status')) {
            $allowedStatuses = ['draft', 'confirmed', 'received', 'billed', 'cancelled'];
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status', $request->status);
            }
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('status', fn (PurchaseOrder $po) => '<span class="badge '.$po->status_badge.'">'.ucfirst($po->status).'</span>')
            ->editColumn('total', fn (PurchaseOrder $po) => number_format($po->total, 2))
            ->editColumn('po_date', fn (PurchaseOrder $po) => $po->po_date?->format('d M Y'))
            ->editColumn('expected_delivery', fn (PurchaseOrder $po) => $po->expected_delivery?->format('d M Y') ?? '-')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (PurchaseOrder $po) => view('purchase_orders.partials.actions', ['row' => $po])->render())
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-purchase-orders');

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'company_name', 'currency_id', 'default_payment_term']);
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::with('tax:id,rate')->where('is_active', true)->get(['id', 'name', 'buy_price', 'unit', 'tax_id']);
        $taxes = \App\Models\Tax::where('is_active', true)->get();
        $units = \App\Support\Units::all();
$paymentTerms = \App\Support\PaymentTerms::all();
        $incoterms = \App\Support\Incoterms::all();
        $defaultTax = \App\Models\Tax::purchases()->where('is_default', true)->first();
        $rates = \App\Models\Currency::where('is_active', true)->pluck('rate', 'id');
        $defaultTerm = \App\Support\PaymentTerms::defaultFor('purchase_order');

        return view('purchase_orders.create', compact('suppliers', 'currencies', 'products', 'taxes', 'units', 'paymentTerms', 'incoterms', 'defaultTax', 'rates', 'defaultTerm'));
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        $this->authorize('create-purchase-orders');

        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        if ($data['payment_terms'] === 'Custom' && $request->filled('payment_terms_custom')) {
            $data['payment_terms'] = $request->input('payment_terms_custom');
        }
        if (($data['delivery_terms'] ?? null) === 'Custom' && $request->filled('delivery_terms_custom')) {
            $data['delivery_terms'] = $request->input('delivery_terms_custom');
        }

        $po = $this->service->createWithItems($data, $items);

        return redirect()->route('purchase_orders.show', $po)->with('success', 'Purchase order created.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('view-purchase-orders');
        $purchaseOrder->load(['supplier', 'currency', 'items.product', 'supplierBills']);
        $purchaseOrder->load(['documents' => fn ($q) => $q->where('is_latest', true)]);

        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('update-purchase-orders');
        $purchaseOrder->load('items');

        if (!in_array($purchaseOrder->status, ['draft'])) {
            return back()->with('error', 'Only draft purchase orders can be edited.');
        }

        $suppliers = \App\Models\Supplier::where('is_active', true)->get(['id', 'company_name', 'currency_id', 'default_payment_term']);
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::with('tax:id,rate')->where('is_active', true)->get(['id', 'name', 'buy_price', 'unit', 'tax_id']);
        $taxes = \App\Models\Tax::where('is_active', true)->get();
        $units = \App\Support\Units::all();
        $paymentTerms = \App\Support\PaymentTerms::all();
        $incoterms = \App\Support\Incoterms::all();
        $defaultTax = \App\Models\Tax::purchases()->where('is_default', true)->first();
        $rates = \App\Models\Currency::where('is_active', true)->pluck('rate', 'id');
        $defaultTerm = \App\Support\PaymentTerms::defaultFor('purchase_order');

        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'currencies', 'products', 'taxes', 'units', 'paymentTerms', 'incoterms', 'defaultTax', 'rates', 'defaultTerm'));
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $this->authorize('update-purchase-orders');

        if (!in_array($purchaseOrder->status, ['draft'])) {
            return back()->with('error', 'Only draft purchase orders can be edited.');
        }

        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        if ($data['payment_terms'] === 'Custom' && $request->filled('payment_terms_custom')) {
            $data['payment_terms'] = $request->input('payment_terms_custom');
        }
        if (($data['delivery_terms'] ?? null) === 'Custom' && $request->filled('delivery_terms_custom')) {
            $data['delivery_terms'] = $request->input('delivery_terms_custom');
        }

        $this->service->updateWithItems($purchaseOrder, $data, $items);

        return redirect()->route('purchase_orders.show', $purchaseOrder)->with('success', 'Purchase order updated.');
    }

    public function status(PurchaseOrder $purchaseOrder, Request $request)
    {
        $this->authorize('update-purchase-orders');

        $request->validate([
            'status' => 'required|in:draft,confirmed,received,billed,cancelled',
        ]);

        $allowed = [
            'draft' => ['confirmed', 'cancelled'],
            'confirmed' => ['received', 'cancelled'],
            'received' => ['cancelled'],
            'billed' => [],
            'cancelled' => ['draft'],
        ];

        if (!in_array($request->status, $allowed[$purchaseOrder->status] ?? [])) {
            return back()->with('error', 'Cannot change status from "'.$purchaseOrder->status.'" to "'.$request->status.'". Billed state is set automatically when converting to a supplier bill.');
        }

        $purchaseOrder->update(['status' => $request->status]);

        return back()->with('success', 'Status updated to '.ucfirst($request->status).'.');
    }

    public function pdf(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('view-purchase-orders');
        $purchaseOrder->load(['supplier', 'currency', 'items.product']);

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

        $html = view('purchase_orders.pdf', ['po' => $purchaseOrder, 'company' => $company])->render();

        $pdf = Pdf::loadHtml($html)
            ->setPaper('a4')
            ->set_option('isHtml5ParserEnabled', true)
            ->set_option('isRemoteEnabled', true);

        return $pdf->download(str_replace('/', '-', $purchaseOrder->number).'.pdf');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('delete-purchase-orders');

        if (!in_array($purchaseOrder->status, ['draft', 'cancelled'])) {
            return back()->with('error', 'Only draft or cancelled purchase orders can be deleted.');
        }

        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order deleted.');
    }
}
