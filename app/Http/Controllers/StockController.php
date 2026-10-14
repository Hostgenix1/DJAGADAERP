<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    /** Stock levels per product (and per warehouse when filtered). */
    public function index()
    {
        $this->authorize('view-stock');

        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $totalProducts = Product::where('is_active', true)->count();
        $lowStock = StockService::lowStockProducts()->count();
        $stockValue = StockService::stockValue();

        return view('stock.index', compact('warehouses', 'totalProducts', 'lowStock', 'stockValue'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-stock');

        $query = Product::where('is_active', true)->with(['brand', 'category', 'currency'])
            ->withSum('stockMovements as stock_total', 'qty');

        if ($request->filled('warehouse_id')) {
            $wid = (int) $request->warehouse_id;
            $query->withSum(['stockMovements as stock_total' => fn ($q) => $q->where('warehouse_id', $wid)], 'qty');
        }

        if ($request->boolean('low_only')) {
            $query->whereNotNull('reorder_level')->where('reorder_level', '>', 0)
                ->havingRaw('COALESCE(stock_total, 0) <= products.reorder_level');
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('name', fn (Product $p) => e($p->name).'<div class="text-muted small">'.e($p->sku).'</div>')
            ->editColumn('category', fn (Product $p) => e($p->category?->name ?? '-') . ' / ' . e($p->brand?->name ?? '-'))
            ->editColumn('stock_total', function (Product $p) {
                $stock = (float) ($p->stock_total ?? 0);
                $low = $p->reorder_level !== null && (float) $p->reorder_level > 0 && $stock <= (float) $p->reorder_level;
                $badge = $low ? ' <span class="badge badge-danger">Low</span>' : '';

                return number_format($stock, 2).$badge;
            })
            ->editColumn('reorder_level', fn (Product $p) => $p->reorder_level !== null ? number_format((float) $p->reorder_level, 2) : '-')
            ->editColumn('buy_price', fn (Product $p) => $p->currency?->code.' '.number_format((float) $p->buy_price, 2))
            ->addColumn('value', fn (Product $p) => $p->currency?->code.' '.number_format(max(0, (float) ($p->stock_total ?? 0)) * (float) $p->buy_price, 2))
            ->rawColumns(['name'])
            ->make(true);
    }

    /** Movement ledger. */
    public function movements(Request $request)
    {
        $this->authorize('view-stock');

        $warehouses = Warehouse::orderBy('name')->pluck('name', 'id');
        $products = Product::orderBy('name')->pluck('name', 'id');

        return view('stock.movements', compact('warehouses', 'products'));
    }

    public function movementsDatatable(Request $request)
    {
        $this->authorize('view-stock');

        $query = StockMovement::query()->with(['product', 'warehouse', 'creator']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->integer('warehouse_id'));
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('movement_date', fn (StockMovement $m) => $m->movement_date?->format('d M Y'))
            ->editColumn('product_id', fn (StockMovement $m) => e($m->product?->name ?? '-').'<div class="text-muted small">'.e($m->product?->sku).'</div>')
            ->editColumn('warehouse_id', fn (StockMovement $m) => e($m->warehouse?->name ?? '-'))
            ->editColumn('type', fn (StockMovement $m) => '<span class="badge '.($m->type === 'in' ? 'badge-success' : ($m->type === 'out' ? 'badge-danger' : 'badge-warning')).'">'.ucfirst($m->type).'</span>')
            ->editColumn('qty', fn (StockMovement $m) => ((float) $m->qty >= 0 ? '+' : '').number_format((float) $m->qty, 2))
            ->editColumn('unit_cost', fn (StockMovement $m) => $m->unit_cost !== null ? number_format((float) $m->unit_cost, 2) : '-')
            ->editColumn('created_by', fn (StockMovement $m) => e($m->creator?->name ?? '-'))
            ->addColumn('actions', fn (StockMovement $m) => view('stock.partials.actions', ['row' => $m])->render())
            ->rawColumns(['product_id', 'type', 'actions'])
            ->make(true);
    }

    public function createMovement()
    {
        $this->authorize('create-stock-movements');

        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('stock.create', compact('warehouses', 'products'));
    }

    public function storeMovement(Request $request)
    {
        $this->authorize('create-stock-movements');

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:in,out,adjustment',
            'qty' => 'required|numeric|not_in:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:100',
            'movement_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($data['type'] === 'out' && (float) $data['qty'] < 0) {
            $data['qty'] = abs((float) $data['qty']);
        }

        StockService::record($data);

        return redirect()->route('stock.movements')->with('success', 'Stock movement recorded.');
    }

    public function destroyMovement(StockMovement $stockMovement)
    {
        $this->authorize('delete-stock-movements');

        // Deleting an "out" movement adds stock back (fine). Deleting an "in"
        // movement must not drive stock below zero.
        if ((float) $stockMovement->qty > 0) {
            $after = StockService::currentStock((int) $stockMovement->product_id, (int) $stockMovement->warehouse_id) - (float) $stockMovement->qty;
            if ($after < 0) {
                return redirect()->route('stock.movements')->with('error', 'Cannot delete: stock was already consumed (would go negative).');
            }
        }

        $stockMovement->delete();

        return redirect()->route('stock.movements')->with('success', 'Stock movement deleted.');
    }
}
