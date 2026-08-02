<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function __construct(protected OrderService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-orders');

        $totalOrders = Order::count();
        $draft = Order::where('status', 'draft')->count();
        $confirmed = Order::where('status', 'confirmed')->count();
        $processing = Order::where('status', 'processing')->count();
        $completed = Order::where('status', 'completed')->count();
        $totalAmount = (float) Order::where('status', '!=', 'cancelled')->sum('total');
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $orderByCurrency = Order::where('status', '!=', 'cancelled')
            ->join('currencies', 'orders.currency_id', '=', 'currencies.id')
            ->selectRaw('currencies.code, currencies.symbol, COUNT(*) as count, SUM(orders.total) as total')
            ->groupBy('currencies.code', 'currencies.symbol')
            ->get()
            ->toArray();

        return view('orders.index', compact('totalOrders', 'draft', 'confirmed', 'processing', 'completed', 'totalAmount', 'defaultCurrency', 'orderByCurrency'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-orders');

        $query = $this->service->query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('status', fn (Order $o) => '<span class="badge '.$o->status_badge.'">'.ucfirst($o->status).'</span>')
            ->editColumn('total', fn (Order $o) => number_format($o->total, 2))
            ->editColumn('order_date', fn (Order $o) => $o->order_date?->format('d M Y'))
            ->editColumn('expected_delivery', fn (Order $o) => $o->expected_delivery?->format('d M Y'))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (Order $o) => view('orders.partials.actions', ['row' => $o])->render())
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-orders');

        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::where('is_active', true)->get(['id', 'name', 'sell_price', 'unit']);

        return view('orders.create', compact('customers', 'currencies', 'products'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-orders');

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'order_date' => 'required|date',
            'expected_delivery' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_pct' => 'nullable|numeric|min:0|max:100',
        ]);

        $order = $this->service->createWithItems($data, $data['items']);

        return redirect()->route('orders.show', $order)->with('success', 'Order created.');
    }

    public function show(Order $order)
    {
        $this->authorize('view-orders');
        $order->load(['customer', 'currency', 'items.product']);

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorize('update-orders');
        $order->load('items');
        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $currencies = \App\Models\Currency::pluck('code', 'id');
        $products = \App\Models\Product::where('is_active', true)->get(['id', 'name', 'sell_price', 'unit']);

        return view('orders.edit', compact('order', 'customers', 'currencies', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update-orders');

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'order_date' => 'required|date',
            'expected_delivery' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_pct' => 'nullable|numeric|min:0|max:100',
        ]);

        $this->service->updateWithItems($order, $data, $data['items']);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated.');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete-orders');
        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
