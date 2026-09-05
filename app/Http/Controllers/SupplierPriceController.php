<?php

namespace App\Http\Controllers;

use App\Models\SupplierPrice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierPriceController extends Controller
{
    public function index()
    {
        $this->authorize('view-supplier-prices');

        $total = SupplierPrice::count();
        $active = SupplierPrice::where(fn ($w) => $w->whereNull('valid_until')->orWhere('valid_until', '>=', today()))->count();
        $suppliers = SupplierPrice::distinct('supplier_id')->whereNotNull('supplier_id')->count('supplier_id');

        return view('supplier_prices.index', compact('total', 'active', 'suppliers'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-supplier-prices');

        $query = SupplierPrice::query()->with(['supplier', 'product', 'currency']);

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('supplier_id', fn (SupplierPrice $sp) => e($sp->supplier?->company_name ?? '-'))
            ->editColumn('product_id', fn (SupplierPrice $sp) => e($sp->product?->name ?? '-'))
            ->editColumn('date_received', fn (SupplierPrice $sp) => $sp->date_received?->format('d M Y'))
            ->editColumn('supplier_price', fn (SupplierPrice $sp) => number_format((float) $sp->supplier_price, 2))
            ->editColumn('currency_id', fn (SupplierPrice $sp) => $sp->currency?->code ?? '-')
            ->editColumn('incoterm', fn (SupplierPrice $sp) => strtoupper($sp->incoterm ?? '-'))
            ->editColumn('container_type', fn (SupplierPrice $sp) => $sp->container_type ? strtoupper($sp->container_type) : '-')
            ->editColumn('valid_until', fn (SupplierPrice $sp) => $sp->valid_until?->format('d M Y') ?? 'No expiry')
            ->editColumn('source', fn (SupplierPrice $sp) => '<span class="badge badge-light">'.ucfirst($sp->source).'</span>')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (SupplierPrice $sp) => view('supplier_prices.partials.actions', ['row' => $sp])->render())
            ->rawColumns(['source', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-supplier-prices');

        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('company_name')->pluck('company_name', 'id');
        $products = \App\Models\Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $incoterms = \App\Support\Incoterms::all();

        return view('supplier_prices.create', compact('suppliers', 'products', 'currencies', 'incoterms'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-supplier-prices');

        $data = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'product_id' => 'nullable|exists:products,id',
            'packaging' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'supplier_price' => 'required|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'incoterm' => 'nullable|string|max:50',
            'destination_port' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric|min:0',
            'container_quantity' => 'nullable|numeric|min:0',
            'container_type' => 'nullable|in:20ft,40ft',
            'date_received' => 'required|date',
            'valid_until' => 'nullable|date',
            'source' => 'required|in:whatsapp,email,other',
            'notes' => 'nullable|string|max:1000',
        ]);

        SupplierPrice::create($data);

        return redirect()->route('supplier_prices.index')->with('success', 'Supplier price recorded.');
    }

    public function edit(SupplierPrice $supplierPrice)
    {
        $this->authorize('update-supplier-prices');

        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('company_name')->pluck('company_name', 'id');
        $products = \App\Models\Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $incoterms = \App\Support\Incoterms::all();

        return view('supplier_prices.edit', compact('supplierPrice', 'suppliers', 'products', 'currencies', 'incoterms'));
    }

    public function update(Request $request, SupplierPrice $supplierPrice)
    {
        $this->authorize('update-supplier-prices');

        $data = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'product_id' => 'nullable|exists:products,id',
            'packaging' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'supplier_price' => 'required|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'incoterm' => 'nullable|string|max:50',
            'destination_port' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric|min:0',
            'container_quantity' => 'nullable|numeric|min:0',
            'container_type' => 'nullable|in:20ft,40ft',
            'date_received' => 'required|date',
            'valid_until' => 'nullable|date',
            'source' => 'required|in:whatsapp,email,other',
            'notes' => 'nullable|string|max:1000',
        ]);

        $supplierPrice->update($data);

        return redirect()->route('supplier_prices.index')->with('success', 'Supplier price updated.');
    }

    public function destroy(SupplierPrice $supplierPrice)
    {
        $this->authorize('delete-supplier-prices');

        $supplierPrice->delete();

        return redirect()->route('supplier_prices.index')->with('success', 'Supplier price deleted.');
    }
}
