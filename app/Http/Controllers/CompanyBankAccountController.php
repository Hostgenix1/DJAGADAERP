<?php

namespace App\Http\Controllers;

use App\Models\CompanyBankAccount;
use App\Services\CompanyBankAccountService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CompanyBankAccountController extends Controller
{
    public function __construct(protected CompanyBankAccountService $service) {}

    public function index()
    {
        $this->authorize('view-bank-accounts');
        return view('bank_accounts.index');
    }

    public function datatable()
    {
        $this->authorize('view-bank-accounts');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->editColumn('currency', fn ($b) => $b->currency?->code ?? '-')
            ->editColumn('is_default', fn ($b) => $b->is_default
                ? '<span class="badge badge-success">Yes</span>'
                : '<span class="badge badge-secondary">No</span>')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->addColumn('actions', function ($row) {
                $editUrl = route('bank-accounts.edit', $row);
                $destroyUrl = route('bank-accounts.destroy', $row);

                return '<a href="'.$editUrl.'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
                <form method="POST" action="'.$destroyUrl.'" class="d-inline" onsubmit="return confirm(\'Delete this bank account?\');">
                    '.csrf_field().method_field('DELETE').'
                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                </form>';
            })
            ->rawColumns(['is_default', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-bank-accounts');
        $currencies = \App\Models\Currency::pluck('code', 'id');

        return view('bank_accounts.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-bank-accounts');

        $data = $request->validate([
            'bank_name'      => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'iban'           => 'nullable|string|max:100',
            'swift_code'     => 'nullable|string|max:20',
            'currency_id'    => 'required|exists:currencies,id',
            'is_default'     => 'boolean',
            'is_active'      => 'boolean',
            'notes'          => 'nullable|string|max:500',
        ]);

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active', true);

        if ($data['is_default']) {
            CompanyBankAccount::where('currency_id', $data['currency_id'])->update(['is_default' => false]);
        }

        CompanyBankAccount::create($data);

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account created.');
    }

    public function edit(CompanyBankAccount $bankAccount)
    {
        $this->authorize('update-bank-accounts');
        $currencies = \App\Models\Currency::pluck('code', 'id');

        return view('bank_accounts.edit', compact('bankAccount', 'currencies'));
    }

    public function update(Request $request, CompanyBankAccount $bankAccount)
    {
        $this->authorize('update-bank-accounts');

        $data = $request->validate([
            'bank_name'      => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'iban'           => 'nullable|string|max:100',
            'swift_code'     => 'nullable|string|max:20',
            'currency_id'    => 'required|exists:currencies,id',
            'is_default'     => 'boolean',
            'is_active'      => 'boolean',
            'notes'          => 'nullable|string|max:500',
        ]);

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active']  = $request->boolean('is_active', true);

        if ($data['is_default']) {
            CompanyBankAccount::where('currency_id', $data['currency_id'])
                ->where('id', '!=', $bankAccount->id)
                ->update(['is_default' => false]);
        }

        $bankAccount->update($data);

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account updated.');
    }

    public function destroy(CompanyBankAccount $bankAccount)
    {
        $this->authorize('delete-bank-accounts');
        $bankAccount->delete();

        return redirect()->route('bank-accounts.index')->with('success', 'Bank account deleted.');
    }
}
