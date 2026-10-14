<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    public function index()
    {
        $this->authorize('view-warehouses');

        $total = Warehouse::count();
        $active = Warehouse::where('is_active', true)->count();

        return view('warehouses.index', compact('total', 'active'));
    }

    public function datatable()
    {
        $this->authorize('view-warehouses');

        $query = Warehouse::query();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('is_active', fn (Warehouse $w) => $w->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>')
            ->addColumn('products_count', fn (Warehouse $w) => $w->stockMovements()->distinct('product_id')->count('product_id'))
            ->addColumn('actions', fn (Warehouse $w) => view('warehouses.partials.actions', ['row' => $w])->render())
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-warehouses');

        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create-warehouses');

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:30',
            'location' => 'nullable|string|max:255',
            'is_active' => 'nullable',
            'notes' => 'nullable|string|max:500',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        Warehouse::create($data);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse created.');
    }

    public function edit(Warehouse $warehouse)
    {
        $this->authorize('update-warehouses');

        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $this->authorize('update-warehouses');

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:30',
            'location' => 'nullable|string|max:255',
            'is_active' => 'nullable',
            'notes' => 'nullable|string|max:500',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $warehouse->update($data);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $this->authorize('delete-warehouses');

        if ($warehouse->stockMovements()->exists()) {
            return redirect()->route('warehouses.index')->with('error', 'Warehouse has stock movements and cannot be deleted.');
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted.');
    }
}
