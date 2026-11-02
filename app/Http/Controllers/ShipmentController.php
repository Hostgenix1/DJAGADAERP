<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\SettingsService;
use App\Services\ShipmentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ShipmentController extends Controller
{
    public function __construct(protected ShipmentService $service) {}

    public function index()
    {
        $this->authorize('view-shipments');

        $total = Shipment::count();
        $preparing = Shipment::where('status', 'preparing')->count();
        $inTransit = Shipment::where('status', 'in_transit')->count();
        $customs = Shipment::where('status', 'customs')->count();
        $delivered = Shipment::where('status', 'delivered')->count();
        $customers = Customer::orderBy('company_name')->pluck('company_name', 'id');

        return view('shipments.index', compact('total', 'preparing', 'inTransit', 'customs', 'delivered', 'customers'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-shipments');

        $filters = $request->validate([
            'status' => 'nullable|in:preparing,in_transit,customs,delivered,cancelled',
            'customer_id' => 'nullable|integer',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $query = $this->service->query();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (! empty($filters['from'])) {
            $query->whereDate('shipped_at', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('shipped_at', '<=', $filters['to']);
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

    public function create(Request $request)
    {
        $this->authorize('create-shipments');

        $customers = Customer::pluck('company_name', 'id');
        $orders = Order::pluck('number', 'id');
        $invoices = Invoice::pluck('number', 'id');
        $preselectedOrderId = $request->integer('order_id') ?: null;

        return view('shipments.create', compact('customers', 'orders', 'invoices', 'preselectedOrderId'));
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
            'bl_number' => 'nullable|string|max:100',
            'container_count' => 'nullable|integer|min:1',
            'container_size' => 'nullable|in:20ft,40ft',
            'container_numbers' => 'nullable|string|max:1000',
            'shipping_method' => 'required|in:air,sea,land,courier',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'status' => 'required|in:preparing,in_transit,customs',
            'shipped_at' => 'nullable|date|after_or_equal:today',
            'estimated_arrival' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['number'] = Shipment::nextNumber();

        $shipment = Shipment::create($data);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Shipment created.');
    }

    public function show(Shipment $shipment)
    {
        $this->authorize('view-shipments');
        $shipment->load(['customer', 'order', 'invoice', 'documents' => fn ($q) => $q->where('is_latest', true)]);

        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $this->authorize('update-shipments');
        $shipment->load(['customer', 'order', 'invoice']);

        $customers = Customer::pluck('company_name', 'id');
        $orders = Order::pluck('number', 'id');
        $invoices = Invoice::pluck('number', 'id');

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
            'bl_number' => 'nullable|string|max:100',
            'container_count' => 'nullable|integer|min:1',
            'container_size' => 'nullable|in:20ft,40ft',
            'container_numbers' => 'nullable|string|max:1000',
            'shipping_method' => 'required|in:air,sea,land,courier',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'status' => 'required|in:preparing,in_transit,customs,delivered,cancelled',
            'shipped_at' => 'nullable|date|after_or_equal:today',
            'estimated_arrival' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($data['status'] !== $shipment->status) {
            $allowed = [
                'preparing' => ['in_transit', 'cancelled'],
                'in_transit' => ['customs', 'delivered', 'cancelled'],
                'customs' => ['in_transit', 'delivered', 'cancelled'],
                'delivered' => [],
                'cancelled' => [],
            ];

            if (! in_array($data['status'], $allowed[$shipment->status] ?? [])) {
                return back()->with('error', 'Cannot change status from "'.str_replace('_', ' ', $shipment->status).'" to "'.str_replace('_', ' ', $data['status']).'".');
            }
        }

        if ($data['status'] === 'delivered' && ! $shipment->delivered_at) {
            $data['delivered_at'] = now();
        } elseif ($data['status'] !== 'delivered' && $shipment->delivered_at) {
            $data['delivered_at'] = null;
        }

        $shipment->update($data);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Shipment updated.');
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $this->authorize('update-shipments');

        $data = $request->validate([
            'status' => 'required|in:in_transit,customs,delivered,cancelled',
        ]);

        $allowed = [
            'preparing' => ['in_transit', 'cancelled'],
            'in_transit' => ['customs', 'delivered', 'cancelled'],
            'customs' => ['in_transit', 'delivered', 'cancelled'],
            'delivered' => [],
            'cancelled' => [],
        ];

        if (! in_array($data['status'], $allowed[$shipment->status] ?? [])) {
            return back()->with('error', 'Cannot change status from "'.str_replace('_', ' ', $shipment->status).'" to "'.str_replace('_', ' ', $data['status']).'".');
        }

        $updates = ['status' => $data['status']];

        if ($data['status'] === 'delivered' && ! $shipment->delivered_at) {
            $updates['delivered_at'] = now();
        } elseif ($data['status'] !== 'delivered') {
            $updates['delivered_at'] = null;
        }

        if ($data['status'] === 'in_transit' && ! $shipment->shipped_at) {
            $updates['shipped_at'] = now();
        }

        $shipment->update($updates);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Shipment status updated to '.ucfirst(str_replace('_', ' ', $data['status'])).'.');
    }

    public function pdf(Shipment $shipment)
    {
        $this->authorize('view-shipments');
        $shipment->load(['customer', 'order', 'invoice']);

        $svc = app(SettingsService::class);
        $logoPath = $svc->get('company_logo');
        $logoBase64 = null;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $fullPath = Storage::disk('public')->path($logoPath);
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $mime = match ($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                default => 'image/png',
            };
            $logoBase64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($fullPath));
        }

        $company = [
            'name' => $svc->get('company_name'),
            'address' => $svc->get('company_address'),
            'city' => $svc->get('company_city'),
            'country' => $svc->get('company_country'),
            'email' => $svc->get('company_email'),
            'phone' => $svc->get('company_phone'),
            'trn' => $svc->get('company_trn'),
            'show_logo' => $svc->get('show_logo_on_docs'),
            'logo_url' => $logoBase64,
        ];

        $pdf = Pdf::loadView('shipments.pdf', compact('shipment', 'company'))
            ->setPaper('a4');

        return $pdf->stream('shipment-'.$shipment->number.'.pdf');
    }

    public function destroy(Shipment $shipment)
    {
        $this->authorize('delete-shipments');
        $shipment->delete();

        return redirect()->route('shipments.index')->with('success', 'Shipment deleted.');
    }
}
