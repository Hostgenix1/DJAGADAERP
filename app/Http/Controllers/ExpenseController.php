<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    public function index()
    {
        $this->authorize('view-expenses');

        $totalOperating = Expense::where('category', 'operating')->sum('amount');
        $totalPayroll = Expense::where('category', 'payroll')->sum('amount');
        $totalAll = Expense::sum('amount');
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        return view('expenses.index', compact('totalOperating', 'totalPayroll', 'totalAll', 'defaultCurrency'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-expenses');

        $query = Expense::query()->with('currency');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('expense_date', fn (Expense $e) => $e->expense_date?->format('d M Y'))
            ->editColumn('amount', fn (Expense $e) => $e->amount)
            ->editColumn('category', fn (Expense $e) => '<span class="badge '.(in_array($e->category, ['payroll']) ? 'badge-dark' : 'badge-warning').'">'.ucfirst($e->category).'</span>')
            ->editColumn('currency_id', fn (Expense $e) => $e->currency?->code ?? '-')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (Expense $e) => view('expenses.partials.actions', ['row' => $e])->render())
            ->rawColumns(['category', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-expenses');

        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $categories = (new Expense)->categories();

        return view('expenses.create', compact('currencies', 'categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-expenses');

        $data = $request->validate([
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'category' => 'required|in:operating,payroll,transport,rent,bank,office,other',
            'description' => 'nullable|string|max:500',
            'paid_to' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense created.');
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update-expenses');

        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $categories = (new Expense)->categories();

        return view('expenses.edit', compact('expense', 'currencies', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update-expenses');

        $data = $request->validate([
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'category' => 'required|in:operating,payroll,transport,rent,bank,office,other',
            'description' => 'nullable|string|max:500',
            'paid_to' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete-expenses');

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}