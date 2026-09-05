<?php

namespace App\Http\Controllers;

use App\Models\SellingPrice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SellingPriceController extends Controller
{
    public function index()
    {
        $this->authorize('view-selling-prices');

        $total = SellingPrice::count();
        $approved = SellingPrice::currentlyApproved()->count();
        $expired = SellingPrice::where('status', 'expired')->count();
        $aiReady = SellingPrice::currentlyApproved()->where('approved_for_ai', true)->count();

        return view('selling_prices.index', compact('total', 'approved', 'expired', 'aiReady'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-selling-prices');

        // Supplier costs and margins are confidential: their columns and JSON
        // values are only included for users with the view-pricing-costs ability.
        $canSeeCosts = auth()->user()->can('view-pricing-costs');

        $query = SellingPrice::query()->with(['customer', 'product', 'currency', 'supplierPrice.supplier']);

        $dt = DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('customer_id', fn (SellingPrice $p) => e($p->customer?->company_name ?? 'General'))
            ->editColumn('product_id', fn (SellingPrice $p) => e($p->product?->name ?? '-'))
            ->editColumn('selling_price', fn (SellingPrice $p) => number_format((float) $p->selling_price, 2))
            ->editColumn('currency_id', fn (SellingPrice $p) => $p->currency?->code ?? '-')
            ->editColumn('incoterm', fn (SellingPrice $p) => strtoupper($p->incoterm ?? '-'))
            ->editColumn('min_qty', fn (SellingPrice $p) => $p->min_qty !== null ? number_format((float) $p->min_qty, 2) : '-')
            ->editColumn('valid_until', fn (SellingPrice $p) => $p->valid_until?->format('d M Y') ?? 'No expiry')
            ->editColumn('status', fn (SellingPrice $p) => '<span class="badge '.match ($p->status) {
                'approved' => 'badge-success',
                'expired' => 'badge-secondary',
                default => 'badge-warning',
            }.'">'.ucfirst($p->status).'</span>')
            ->editColumn('approved_for_ai', fn (SellingPrice $p) => $p->approved_for_ai ? '<span class="badge badge-info">AI</span>' : '<span class="badge badge-light">No</span>')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (SellingPrice $p) => view('selling_prices.partials.actions', ['row' => $p])->render())
            ->rawColumns(['status', 'approved_for_ai', 'actions']);

        if ($canSeeCosts) {
            $dt->editColumn('supplier_cost', fn (SellingPrice $p) => number_format((float) $p->supplier_cost, 2))
                ->editColumn('margin', fn (SellingPrice $p) => $p->margin_pct !== null && (float) $p->margin_pct != 0
                    ? rtrim(rtrim($p->margin_pct, '0'), '.').'%'
                    : number_format((float) $p->margin_amount, 2))
                ->rawColumns(['status', 'approved_for_ai', 'actions']);
        }

        return $dt->make(true);
    }

    public function create()
    {
        $this->authorize('create-selling-prices');

        $supplierPrices = \App\Models\SupplierPrice::with(['supplier', 'product', 'currency'])
            ->latest('date_received')->limit(200)->get();
        $customers = \App\Models\Customer::orderBy('company_name')->pluck('company_name', 'id');
        $products = \App\Models\Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $incoterms = \App\Support\Incoterms::all();
        $canSeeCosts = auth()->user()->can('view-pricing-costs');

        return view('selling_prices.create', compact('supplierPrices', 'customers', 'products', 'currencies', 'incoterms', 'canSeeCosts'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-selling-prices');

        $data = $this->validated($request);
        $data = $this->applyAutoPricing($data);

        SellingPrice::create($data);

        return redirect()->route('selling_prices.index')->with('success', 'Selling price created.');
    }

    public function edit(SellingPrice $sellingPrice)
    {
        $this->authorize('update-selling-prices');

        $supplierPrices = \App\Models\SupplierPrice::with(['supplier', 'product', 'currency'])
            ->latest('date_received')->limit(200)->get();
        $customers = \App\Models\Customer::orderBy('company_name')->pluck('company_name', 'id');
        $products = \App\Models\Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $incoterms = \App\Support\Incoterms::all();
        $canSeeCosts = auth()->user()->can('view-pricing-costs');

        return view('selling_prices.edit', compact('sellingPrice', 'supplierPrices', 'customers', 'products', 'currencies', 'incoterms', 'canSeeCosts'));
    }

    public function update(Request $request, SellingPrice $sellingPrice)
    {
        $this->authorize('update-selling-prices');

        $data = $this->validated($request);
        $data = $this->applyAutoPricing($data);

        $sellingPrice->update($data);

        return redirect()->route('selling_prices.index')->with('success', 'Selling price updated.');
    }

    public function approve(SellingPrice $sellingPrice)
    {
        $this->authorize('approve-selling-prices');

        if ($sellingPrice->valid_until && $sellingPrice->valid_until->isPast()) {
            return back()->with('error', 'This price is past its Valid Until date — set a new validity first.');
        }

        $sellingPrice->update(['status' => SellingPrice::STATUS_APPROVED]);

        return back()->with('success', 'Selling price approved.');
    }

    public function destroy(SellingPrice $sellingPrice)
    {
        $this->authorize('delete-selling-prices');

        $sellingPrice->delete();

        return redirect()->route('selling_prices.index')->with('success', 'Selling price deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'supplier_price_id' => 'nullable|exists:supplier_prices,id',
            'customer_id' => 'nullable|exists:customers,id',
            'product_id' => 'nullable|exists:products,id',
            'packaging' => 'nullable|string|max:255',
            'supplier_cost' => 'required|numeric|min:0',
            'margin_pct' => 'nullable|numeric|min:0|max:1000',
            'margin_amount' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'destination' => 'nullable|string|max:255',
            'incoterm' => 'nullable|string|max:50',
            'min_qty' => 'nullable|numeric|min:0',
            'valid_until' => 'nullable|date',
            'status' => 'required|in:draft,approved,expired',
            'approved_for_ai' => 'nullable',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['approved_for_ai'] = $request->boolean('approved_for_ai');
        $data['margin_pct'] = $data['margin_pct'] ?? null;
        $data['margin_amount'] = $data['margin_amount'] ?? 0;

        return $data;
    }

    /**
     * Selling price priority: manually entered price wins; otherwise
     * percentage margin, otherwise fixed-amount margin (Cost + Margin).
     */
    private function applyAutoPricing(array $data): array
    {
        $cost = (float) $data['supplier_cost'];

        if ($data['margin_pct'] !== null && (float) $data['margin_pct'] > 0) {
            $data['margin_amount'] = round($cost * (float) $data['margin_pct'] / 100, 2);
        }

        return $data;
    }
}
