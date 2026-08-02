<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    public function __construct(protected CurrencyService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-currencies');

        return view('currencies.index', ['columns' => array (
  0 => 
  array (
    'label' => 'Code',
    'data' => 'code',
  ),
  1 => 
  array (
    'label' => 'Name',
    'data' => 'name',
  ),
  2 => 
  array (
    'label' => 'Symbol',
    'data' => 'symbol',
  ),
  3 => 
  array (
    'label' => 'Rate (vs base)',
    'data' => 'rate',
  ),
  4 => 
  array (
    'label' => 'Active',
    'data' => 'is_active',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-currencies');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Currency $row) {
                return view('currencies.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-currencies');

        return view('currencies.create', );
    }

    public function store(StoreCurrencyRequest $request)
    {
        $this->authorize('create-currencies');

        $this->service->create($request->validated());

        return redirect()->route('currencies.index')->with('success', 'Created successfully.');
    }

    public function edit(Currency $currency)
    {
        $this->authorize('update-currencies');

        return view('currencies.edit', ['currency' => $currency, 'relations' => $relations]);
    }

    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $this->authorize('update-currencies');

        $this->service->update($currency->id, $request->validated());

        return redirect()->route('currencies.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        $this->authorize('delete-currencies');

        $this->service->delete($currency->id);

        return redirect()->route('currencies.index')->with('success', 'Deleted successfully.');
    }
}
