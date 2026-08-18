<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class QuoteController extends Controller
{
    public function __construct(protected QuoteService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-quotes');

        $totalQuotes = Quote::count();
        $totalAmount = (float) Quote::where('status', '!=', 'cancelled')->sum('total');
        $accepted = (float) Quote::where('status', 'accepted')->sum('total');
        $pending = (float) Quote::where('status', 'pending')->sum('total');
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $quoteByCurrency = Quote::where('status', '!=', 'cancelled')
            ->leftJoin('currencies', 'quotes.currency_id', '=', 'currencies.id')
            ->selectRaw('currencies.code, currencies.symbol, COUNT(*) as count, SUM(quotes.total) as total')
            ->groupBy('currencies.code', 'currencies.symbol')
            ->get()
            ->toArray();

        return view('quotes.index', compact('totalQuotes', 'totalAmount', 'accepted', 'pending', 'defaultCurrency', 'quoteByCurrency'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-quotes');

        $query = $this->service->query();

        if ($request->filled('status')) {
            $allowedStatuses = ['draft', 'sent', 'accepted', 'rejected', 'expired', 'converted', 'cancelled'];
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status', $request->status);
            }
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('status', fn (Quote $q) => '<span class="badge '.$q->status_badge.'">'.ucfirst($q->status).'</span>')
            ->editColumn('total', fn (Quote $q) => number_format($q->total, 2))
            ->editColumn('date', fn (Quote $q) => $q->date?->format('d M Y'))
            ->editColumn('valid_until', fn (Quote $q) => $q->valid_until?->format('d M Y'))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (Quote $q) => view('quotes.partials.actions', ['row' => $q])->render())
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-quotes');

        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::where('is_active', true)->get(['id', 'name', 'sell_price', 'unit']);
        $taxes = \App\Models\Tax::sales()->where('is_active', true)->get();
        $units = \App\Support\Units::all();
        $paymentTerms = \App\Support\PaymentTerms::all();
        $incoterms = \App\Support\Incoterms::all();
        $defaultTax = \App\Models\Tax::sales()->where('is_default', true)->first();
        $rates = \App\Models\Currency::where('is_active', true)->pluck('rate', 'id');
        $defaultTermsByType = \App\Support\PaymentTerms::defaultsByType();

        return view('quotes.create', compact('customers', 'currencies', 'products', 'taxes', 'units', 'paymentTerms', 'incoterms', 'defaultTax', 'rates', 'defaultTermsByType'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-quotes');

        $request->merge($this->normalizeCustomFields($request));

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:date',
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

        $quote = $this->service->createWithItems($data, $data['items']);

        return redirect()->route('quotes.show', $quote)->with('success', 'Quote created.');
    }

    public function show(Quote $quote)
    {
        $this->authorize('view-quotes');
        $quote->load(['customer', 'currency', 'items.product']);

        return view('quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $this->authorize('update-quotes');
        $quote->load('items');

        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::where('is_active', true)->get(['id', 'name', 'sell_price', 'unit']);
        $taxes = \App\Models\Tax::sales()->where('is_active', true)->get();
        $units = \App\Support\Units::all();
        $paymentTerms = \App\Support\PaymentTerms::all();
        $incoterms = \App\Support\Incoterms::all();
        $defaultTax = \App\Models\Tax::sales()->where('is_default', true)->first();
        $rates = \App\Models\Currency::where('is_active', true)->pluck('rate', 'id');
        $defaultTermsByType = \App\Support\PaymentTerms::defaultsByType();

        return view('quotes.edit', compact('quote', 'customers', 'currencies', 'products', 'taxes', 'units', 'paymentTerms', 'incoterms', 'defaultTax', 'rates', 'defaultTermsByType'));
    }

    public function update(Request $request, Quote $quote)
    {
        $this->authorize('update-quotes');

        if (in_array($quote->status, ['converted'])) {
            return back()->with('error', 'Converted quotes cannot be edited.');
        }

        $request->merge($this->normalizeCustomFields($request));

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:date',
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

        $this->service->updateWithItems($quote, $data, $data['items']);

        return redirect()->route('quotes.show', $quote)->with('success', 'Quote updated.');
    }

    public function convertToInvoice(Quote $quote, string $type)
    {
        $this->authorize('update-quotes');

        $allowedTypes = ['commercial', 'proforma', 'credit_note', 'packing_list', 'delivery_note'];
        if (!in_array($type, $allowedTypes)) {
            return back()->with('error', 'Invalid invoice type.');
        }

        try {
            $invoice = $this->service->convertToInvoice($quote, $type);
            return redirect()->route('invoices.show', $invoice)->with('success', 'Quote converted to '.ucfirst(str_replace('_', ' ', $type)).'.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pdf(\App\Models\Quote $quote)
    {
        $this->authorize('view-quotes');
        $quote->load(['customer', 'currency', 'items.product']);

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
            'name'         => $svc->get('company_name'),
            'address'      => $svc->get('company_address'),
            'city'         => $svc->get('company_city'),
            'country'      => $svc->get('company_country'),
            'email'        => $svc->get('company_email'),
            'phone'        => $svc->get('company_phone'),
            'tax_id'       => $svc->get('company_tax_id'),
            'registration' => $svc->get('company_registration'),
            'footer'       => $svc->get('company_footer_text'),
            'show_logo'    => $svc->get('show_logo_on_docs'),
            'logo_url'     => $logoBase64,
            'signature_url' => $sigBase64,

            'trade_license' => $svc->get('company_trade_license'),
            'trn'           => $svc->get('company_trn'),
            'free_zone'     => $svc->get('company_free_zone'),
            'entity_type'   => $svc->get('company_entity_type'),
        ];

        $bankAccount = \App\Models\CompanyBankAccount::where('is_active', true)->where('is_default', true)->first()
            ?: \App\Models\CompanyBankAccount::where('is_active', true)->first();

        $company['bank_name']    = $bankAccount?->bank_name ?? $svc->get('company_bank_name');
        $company['bank_account'] = $bankAccount?->account_name ?? $svc->get('company_bank_account');
        $company['bank_number']  = $bankAccount?->account_number ?? '';
        $company['bank_iban']    = $bankAccount?->iban ?? $svc->get('company_bank_iban');
        $company['bank_swift']   = $bankAccount?->swift_code ?? $svc->get('company_bank_swift');
        $company['bank_address'] = $bankAccount?->bank_address ?? $svc->get('company_bank_address');

        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($quote->number);
        $html = view('quotes.pdf', compact('quote', 'company', 'bankAccount', 'qrSvg'))->render();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHtml($html)
            ->setPaper('a4')
            ->set_option('isHtml5ParserEnabled', true)
            ->set_option('isRemoteEnabled', true);
        return $pdf->download(str_replace('/', '-', $quote->number).'.pdf');
    }

    public function destroy(Quote $quote)
    {
        $this->authorize('delete-quotes');

        if (in_array($quote->status, ['converted'])) {
            return back()->with('error', 'Converted quotes cannot be deleted.');
        }

        $quote->items()->delete();
        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Quote deleted.');
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
