<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\ShipmentService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShipmentController extends Controller
{
    public function __construct(protected ShipmentService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-shipments');

        $total = Shipment::count();
        $preparing = Shipment::where('status', 'preparing')->count();
        $inTransit = Shipment::where('status', 'in_transit')->count();
        $delivered = Shipment::where('status', 'delivered')->count();
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $shipmentByCurrency = Shipment::where('status', '!=', 'cancelled')
            ->whereNotNull('total_cost')
            ->join('currencies', 'shipments.currency_id', '=', 'currencies.id')
            ->selectRaw('currencies.code, currencies.symbol, COUNT(*) as count, SUM(shipments.total_cost) as total')
            ->groupBy('currencies.code', 'currencies.symbol')
            ->get()
            ->toArray();

        return view('shipments.index', compact('total', 'preparing', 'inTransit', 'delivered', 'defaultCurrency', 'shipmentByCurrency'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-shipments');

        $query = $this->service->query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('carrier', fn ($s) => $s->carrier ?? '-')
            ->editColumn('tracking_number', fn ($s) => $s->tracking_number ?? '-')
            ->editColumn('shipping_method', fn ($s) => ucfirst($s->shipping_method))
            ->editColumn('status', fn (Shipment $s) => '<span class="badge '.$s->status_badge.'">'.ucfirst(str_replace('_', ' ', $s->status)).'</span>')
            ->editColumn('shipped_at', fn ($s) => $s->shipped_at?->format('d M Y H:i') ?? '-')
            ->editColumn('estimated_arrival', fn ($s) => $s->estimated_arrival?->format('d M Y') ?? '-')
            ->addColumn('actions', fn (Shipment $s) => view('shipments.partials.actions', ['row' => $s])->render())
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-shipments');

        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $orders = \App\Models\Order::pluck('number', 'id');
        $invoices = \App\Models\Invoice::pluck('number', 'id');

        return view('shipments.create', compact('customers', 'orders', 'invoices'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-shipments');

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'carrier' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'shipping_method' => 'required|in:air,sea,land,courier',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'status' => 'required|in:preparing,in_transit,customs,delivered,cancelled',
            'shipped_at' => 'nullable|date',
            'estimated_arrival' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['number'] = Shipment::nextNumber();
        $shipment = Shipment::create($data);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Shipment created.');
    }

    public function show(Shipment $shipment)
    {
        $this->authorize('view-shipments');
        $shipment->load(['customer', 'order', 'invoice']);

        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $this->authorize('update-shipments');
        $shipment->load(['customer', 'order', 'invoice']);

        $customers = \App\Models\Customer::pluck('company_name', 'id');
        $orders = \App\Models\Order::pluck('number', 'id');
        $invoices = \App\Models\Invoice::pluck('number', 'id');

        return view('shipments.edit', compact('shipment', 'customers', 'orders', 'invoices'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $this->authorize('update-shipments');

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'carrier' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'shipping_method' => 'required|in:air,sea,land,courier',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'status' => 'required|in:preparing,in_transit,customs,delivered,cancelled',
            'shipped_at' => 'nullable|date',
            'estimated_arrival' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($data['status'] === 'delivered' && !$shipment->delivered_at) {
            $data['delivered_at'] = now();
        }

        $shipment->update($data);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Shipment updated.');
    }

    public function destroy(Shipment $shipment)
    {
        $this->authorize('delete-shipments');
        $shipment->delete();

        return redirect()->route('shipments.index')->with('success', 'Shipment deleted.');
    }
}
