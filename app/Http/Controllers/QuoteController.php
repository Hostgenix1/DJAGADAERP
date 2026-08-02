<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuoteController extends Controller
{
    public function __construct(protected QuoteService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-quotes');

        return view('quotes.index');
    }

    public function datatable()
    {
        $this->authorize('view-quotes');

        return DataTables::eloquent($this->service->query())
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

        return view('quotes.create', compact('customers', 'currencies', 'products'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-quotes');

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:date',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_pct' => 'nullable|numeric|min:0|max:100',
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

        return view('quotes.edit', compact('quote', 'customers', 'currencies', 'products'));
    }

    public function update(Request $request, Quote $quote)
    {
        $this->authorize('update-quotes');

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:date',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_pct' => 'nullable|numeric|min:0|max:100',
        ]);

        $this->service->updateWithItems($quote, $data, $data['items']);

        return redirect()->route('quotes.show', $quote)->with('success', 'Quote updated.');
    }

    public function convertToInvoice(Quote $quote, string $type)
    {
        $this->authorize('update-quotes');

        $invoice = $this->service->convertToInvoice($quote, $type);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Quote converted to '.ucfirst(str_replace('_', ' ', $type)).'.');
    }

    public function destroy(Quote $quote)
    {
        $this->authorize('delete-quotes');
        $quote->items()->delete();
        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Quote deleted.');
    }
}
