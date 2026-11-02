<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Product;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Services\PurchaseOrderService;
use App\Services\PurchaseRequestService;
use App\Support\Units;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseRequestController extends Controller
{
    public function __construct(protected PurchaseRequestService $service) {}

    public function index()
    {
        $this->authorize('view-purchase-requests');

        $total = PurchaseRequest::count();
        $pending = PurchaseRequest::where('status', 'pending')->count();
        $approved = PurchaseRequest::where('status', 'approved')->count();
        $converted = PurchaseRequest::where('status', 'converted')->count();
        $estimated = (float) PurchaseRequest::whereNotIn('status', ['rejected', 'converted'])->sum('estimated_total');
        $defaultCurrency = Currency::where('is_default', true)->first();

        return view('purchase_requests.index', compact('total', 'pending', 'approved', 'converted', 'estimated', 'defaultCurrency'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-purchase-requests');

        $query = $this->service->query();

        if ($request->filled('status')) {
            $allowed = ['draft', 'pending', 'approved', 'rejected', 'converted'];
            if (in_array($request->status, $allowed, true)) {
                $query->where('status', $request->status);
            }
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('status', fn (PurchaseRequest $pr) => '<span class="badge '.$pr->status_badge.'">'.ucfirst($pr->status).'</span>')
            ->editColumn('priority', fn (PurchaseRequest $pr) => ucfirst($pr->priority))
            ->editColumn('needed_by', fn (PurchaseRequest $pr) => $pr->needed_by?->format('d M Y') ?? '-')
            ->editColumn('estimated_total', fn (PurchaseRequest $pr) => number_format((float) $pr->estimated_total, 2))
            ->addColumn('actions', fn (PurchaseRequest $pr) => view('purchase_requests.partials.actions', ['row' => $pr])->render())
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-purchase-requests');

        return view('purchase_requests.create', $this->formData());
    }

    public function store(Request $request)
    {
        $this->authorize('create-purchase-requests');

        $data = $this->validated($request);
        $items = $data['items'];
        unset($data['items']);

        $data['requested_by'] = auth()->id();
        $data['status'] = 'draft';

        $purchaseRequest = $this->service->createWithItems($data, $items);

        return redirect()->route('purchase_requests.show', $purchaseRequest)->with('success', 'Purchase request created.');
    }

    public function show(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('view-purchase-requests');

        $purchaseRequest->load(['requester', 'approver', 'supplier', 'currency', 'items.product', 'purchaseOrder']);

        return view('purchase_requests.show', compact('purchaseRequest'));
    }

    public function edit(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update-purchase-requests');

        if ($purchaseRequest->status !== 'draft') {
            return back()->with('error', 'Only draft purchase requests can be edited.');
        }

        $purchaseRequest->load('items');

        return view('purchase_requests.edit', ['purchaseRequest' => $purchaseRequest] + $this->formData());
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update-purchase-requests');

        if ($purchaseRequest->status !== 'draft') {
            return back()->with('error', 'Only draft purchase requests can be edited.');
        }

        $data = $this->validated($request);
        $items = $data['items'];
        unset($data['items']);

        $this->service->updateWithItems($purchaseRequest, $data, $items);

        return redirect()->route('purchase_requests.show', $purchaseRequest)->with('success', 'Purchase request updated.');
    }

    public function submit(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('update-purchase-requests');

        if ($purchaseRequest->status !== 'draft') {
            return back()->with('error', 'Only draft requests can be submitted.');
        }

        if ($purchaseRequest->items()->count() === 0) {
            return back()->with('error', 'Add at least one item before submitting.');
        }

        $purchaseRequest->update(['status' => 'pending']);

        return back()->with('success', 'Purchase request submitted for approval.');
    }

    public function approve(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('approve-purchase-requests');

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $purchaseRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        activity()->causedBy(auth()->user())->performedOn($purchaseRequest)->event('approved')->log('purchase request approved');

        return back()->with('success', 'Purchase request approved.');
    }

    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        $this->authorize('approve-purchase-requests');

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $data = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $purchaseRequest->update([
            'status' => 'rejected',
            'notes' => trim(($purchaseRequest->notes ? $purchaseRequest->notes."\n\n" : '').'Rejected: '.($data['rejection_reason'] ?? 'No reason given.')),
        ]);

        activity()->causedBy(auth()->user())->performedOn($purchaseRequest)->withProperties(['reason' => $data['rejection_reason'] ?? null])->event('rejected')->log('purchase request rejected');

        return back()->with('success', 'Purchase request rejected.');
    }

    public function convert(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('create-purchase-orders');

        if ($purchaseRequest->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be converted.');
        }

        if (! $purchaseRequest->supplier_id) {
            return back()->with('error', 'Select a supplier on the request before converting it to a purchase order.');
        }

        $purchaseRequest->load('items');

        $items = $purchaseRequest->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'description' => $item->description,
            'qty' => $item->qty,
            'unit' => $item->unit,
            'unit_price' => $item->unit_price,
            'tax_rate' => null,
            'discount_pct' => 0,
        ])->all();

        $po = app(PurchaseOrderService::class)->createWithItems([
            'supplier_id' => $purchaseRequest->supplier_id,
            'currency_id' => $purchaseRequest->currency_id,
            'po_date' => now()->toDateString(),
            'expected_delivery' => $purchaseRequest->needed_by?->toDateString(),
            'status' => 'draft',
            'vat_mode' => 'excluded',
            'vat_rate' => 0,
            'reference_no' => $purchaseRequest->number,
            'notes' => 'Created from purchase request '.$purchaseRequest->number.'.',
        ], $items);

        $purchaseRequest->update([
            'status' => 'converted',
            'purchase_order_id' => $po->id,
        ]);

        activity()->causedBy(auth()->user())->performedOn($purchaseRequest)->withProperties(['purchase_order_id' => $po->id])->event('converted')->log('purchase request converted to purchase order');

        return redirect()->route('purchase_orders.show', $po)->with('success', 'Purchase request converted to purchase order '.$po->number.'.');
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('delete-purchase-requests');

        if (! in_array($purchaseRequest->status, ['draft', 'rejected'], true)) {
            return back()->with('error', 'Only draft or rejected requests can be deleted.');
        }

        $purchaseRequest->items()->delete();
        $purchaseRequest->delete();

        return redirect()->route('purchase_requests.index')->with('success', 'Purchase request deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'department' => 'nullable|string|max:100',
            'needed_by' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,normal,high,urgent',
            'justification' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
    }

    private function formData(): array
    {
        return [
            'suppliers' => Supplier::where('is_active', true)->orderBy('company_name')->pluck('company_name', 'id'),
            'currencies' => Currency::where('is_active', true)->pluck('code', 'id'),
            'products' => Product::where('is_active', true)->orderBy('name')->get(['id', 'name', 'unit', 'buy_price']),
            'units' => Units::all(),
        ];
    }
}
