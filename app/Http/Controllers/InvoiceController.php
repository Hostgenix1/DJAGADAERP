<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-invoices');

        $totalInvoices = Invoice::count();
        $totalAmount = (float) Invoice::where('status', '!=', 'cancelled')->sum('total');
        $totalPaid = (float) Invoice::where('status', '!=', 'cancelled')->sum('paid_amount');
        $totalOutstanding = $totalAmount - $totalPaid;
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $invoiceByCurrency = Invoice::where('status', '!=', 'cancelled')
            ->leftJoin('currencies', 'invoices.currency_id', '=', 'currencies.id')
            ->selectRaw('currencies.code, currencies.symbol, COUNT(*) as count, SUM(invoices.total) as total, SUM(invoices.paid_amount) as paid')
            ->groupBy('currencies.code', 'currencies.symbol')
            ->get()
            ->toArray();

        return view('invoices.index', compact('totalInvoices', 'totalAmount', 'totalPaid', 'totalOutstanding', 'defaultCurrency', 'invoiceByCurrency'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-invoices');

        $query = $this->service->query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $allowedStatuses = ['draft', 'sent', 'paid', 'partial', 'overdue', 'cancelled'];
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status', $request->status);
            }
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('type', fn (Invoice $i) => ucfirst(str_replace('_', ' ', $i->type)))
            ->editColumn('status', fn (Invoice $i) => '<span class="badge '.$i->status_badge.'">'.ucfirst($i->status).'</span>')
            ->editColumn('total', fn (Invoice $i) => number_format($i->total, 2))
            ->editColumn('balance', fn (Invoice $i) => '<span class="'.($i->balance > 0 ? 'text-danger' : 'text-success').'">'.number_format($i->balance, 2).'</span>')
            ->editColumn('invoice_date', fn (Invoice $i) => $i->invoice_date?->format('d M Y'))
            ->editColumn('due_date', fn (Invoice $i) => $i->due_date?->format('d M Y'))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (Invoice $i) => view('invoices.partials.actions', ['row' => $i])->render())
            ->rawColumns(['status', 'balance', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-invoices');
        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::where('is_active', true)->get(['id', 'name', 'sell_price', 'unit']);
        $types = ['commercial', 'proforma', 'credit_note', 'packing_list', 'delivery_note'];
        $bankAccounts = \App\Models\CompanyBankAccount::where('is_active', true)->with('currency')->get();
        $taxes = \App\Models\Tax::sales()->where('is_active', true)->get();
        $units = \App\Support\Units::all();
        $paymentTerms = \App\Support\PaymentTerms::all();
        $incoterms = \App\Support\Incoterms::all();
        $defaultTax = \App\Models\Tax::sales()->where('is_default', true)->first();
        $rates = \App\Models\Currency::where('is_active', true)->pluck('rate', 'id');
        $defaultTermsByType = \App\Support\PaymentTerms::defaultsByType();

        return view('invoices.create', compact('customers', 'currencies', 'products', 'types', 'bankAccounts', 'taxes', 'units', 'paymentTerms', 'incoterms', 'defaultTax', 'rates', 'defaultTermsByType'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-invoices');

        $request->merge($this->normalizeCustomFields($request));

        $data = $request->validate([
            'type' => 'required|in:commercial,proforma,credit_note,packing_list,delivery_note',
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'bank_account_id' => 'nullable|exists:company_bank_accounts,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:1000',
            'reference_no' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:1000',
            'delivery_terms' => 'nullable|string|max:500',
            'delivery_terms_custom' => 'nullable|string|max:500',
            'port_of_loading' => 'nullable|string|max:500',
            'port_of_discharge' => 'nullable|string|max:500',
            'goods_origin' => 'nullable|string|max:500',
            'offer_valid' => 'nullable|integer|min:1|max:365',
            'vat_mode' => 'required|in:none,excluded,included',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.sub_description' => 'nullable|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_pct' => 'nullable|numeric|min:0|max:100',
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $invoice = $this->service->createWithItems($data, $data['items']);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view-invoices');
        $invoice->load(['customer', 'currency', 'items.product', 'payments']);
        $invoice->load(['documents' => fn ($q) => $q->where('is_latest', true)]);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorize('update-invoices');
        $invoice->load('items');
        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::where('is_active', true)->get(['id', 'name', 'sell_price', 'unit']);
        $types = ['commercial', 'proforma', 'credit_note', 'packing_list', 'delivery_note'];
        $bankAccounts = \App\Models\CompanyBankAccount::where('is_active', true)->with('currency')->get();
        $taxes = \App\Models\Tax::sales()->where('is_active', true)->get();
        $units = \App\Support\Units::all();
        $paymentTerms = \App\Support\PaymentTerms::all();
        $incoterms = \App\Support\Incoterms::all();
        $defaultTax = \App\Models\Tax::sales()->where('is_default', true)->first();
        $rates = \App\Models\Currency::where('is_active', true)->pluck('rate', 'id');
        $defaultTermsByType = \App\Support\PaymentTerms::defaultsByType();

        return view('invoices.edit', compact('invoice', 'customers', 'currencies', 'products', 'types', 'bankAccounts', 'taxes', 'units', 'paymentTerms', 'incoterms', 'defaultTax', 'rates', 'defaultTermsByType'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize('update-invoices');

        if (!in_array($invoice->status, ['draft'])) {
            return back()->with('error', 'Only draft invoices can be edited.');
        }

        $request->merge($this->normalizeCustomFields($request));

        $data = $request->validate([
            'type' => 'required|in:commercial,proforma,credit_note,packing_list,delivery_note',
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'bank_account_id' => 'nullable|exists:company_bank_accounts,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:1000',
            'reference_no' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:1000',
            'delivery_terms' => 'nullable|string|max:500',
            'delivery_terms_custom' => 'nullable|string|max:500',
            'port_of_loading' => 'nullable|string|max:500',
            'port_of_discharge' => 'nullable|string|max:500',
            'goods_origin' => 'nullable|string|max:500',
            'offer_valid' => 'nullable|integer|min:1|max:365',
            'vat_mode' => 'required|in:none,excluded,included',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.sub_description' => 'nullable|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_pct' => 'nullable|numeric|min:0|max:100',
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->service->updateWithItems($invoice, $data, $data['items']);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated.');
    }

    public function pdf(Invoice $invoice)
    {
        $this->authorize('view-invoices');
        $invoice->load(['customer', 'currency', 'items.product', 'bankAccount']);

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
        ];

        if ($invoice->bankAccount) {
            $company['bank_name']    = $invoice->bankAccount->bank_name;
            $company['bank_account'] = $invoice->bankAccount->account_name;
            $company['bank_number']  = $invoice->bankAccount->account_number;
            $company['bank_iban']    = $invoice->bankAccount->iban;
            $company['bank_swift']   = $invoice->bankAccount->swift_code;
            $company['bank_address'] = $invoice->bankAccount->bank_address;
        } else {
            $company['bank_name']    = $svc->get('company_bank_name');
            $company['bank_account'] = $svc->get('company_bank_account');
            $company['bank_number']  = '';
            $company['bank_iban']    = $svc->get('company_bank_iban');
            $company['bank_swift']   = $svc->get('company_bank_swift');
            $company['bank_address'] = $svc->get('company_bank_address');
        }

        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($invoice->number);

        $html = view('invoices.pdf', compact('invoice', 'qrSvg', 'company'))->render();

        $pdf = Pdf::loadHtml($html)
            ->setPaper('a4')
            ->set_option('isHtml5ParserEnabled', true)
            ->set_option('isRemoteEnabled', true);

        return $pdf->download(str_replace('/', '-', $invoice->number).'.pdf');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete-invoices');

        if (!in_array($invoice->status, ['draft', 'cancelled'])) {
            return back()->with('error', 'Only draft or cancelled invoices can be deleted.');
        }

        $invoice->items()->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted.');
    }

    protected function normalizeCustomFields(Request $request): array
    {
        $merged = [];

        if ($request->input('payment_terms') === 'Custom' && $request->filled('payment_terms_custom')) {
            $merged['payment_terms'] = $request->input('payment_terms_custom');
        }

        if ($request->input('delivery_terms') === 'Custom' && $request->filled('delivery_terms_custom')) {
            $merged['delivery_terms'] = $request->input('delivery_terms_custom');
        }

        if ($request->input('vat_rate') === 'custom') {
            $merged['vat_rate'] = $request->input('vat_rate_custom');
        }

        return $merged;
    }
}
