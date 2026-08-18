<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
{
    public function index()
    {
        $this->authorize('view-payroll');

        $period = request('period', now()->format('Y-m'));

        $entries = PayrollEntry::where('period', $period)->with('employee');
        $totalGross = (clone $entries)->sum('gross_salary');
        $totalNet = (clone $entries)->sum('net_salary');
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $periods = PayrollEntry::query()->select('period')->distinct()->orderByDesc('period')->pluck('period');

        return view('payroll.index', compact('period', 'totalGross', 'totalNet', 'defaultCurrency', 'periods'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-payroll');

        $query = PayrollEntry::query()->with(['employee', 'currency']);

        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('employee_id', fn (PayrollEntry $p) => $p->employee?->name ?? '-')
            ->editColumn('gross_salary', fn (PayrollEntry $p) => $p->gross_salary)
            ->editColumn('allowances', fn (PayrollEntry $p) => $p->allowances)
            ->editColumn('deductions', fn (PayrollEntry $p) => $p->deductions)
            ->editColumn('net_salary', fn (PayrollEntry $p) => $p->net_salary)
            ->editColumn('currency_id', fn (PayrollEntry $p) => $p->currency?->code ?? '-')
            ->editColumn('status', fn (PayrollEntry $p) => '<span class="badge '.($p->status === 'paid' ? 'badge-success' : 'badge-warning').'">'.ucfirst($p->status).'</span>')
            ->editColumn('paid_on', fn (PayrollEntry $p) => $p->paid_on?->format('d M Y') ?? '-')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (PayrollEntry $p) => view('payroll.partials.actions', ['row' => $p])->render())
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-payroll');

        $employees = Employee::where('is_active', true)->orderBy('name')->get(['id', 'name', 'base_salary', 'currency_id']);
        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');

        return view('payroll.create', compact('employees', 'currencies'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-payroll');

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|regex:/^\d{4}-\d{2}$/',
            'gross_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'status' => 'required|in:draft,approved,paid',
            'paid_on' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $employee = Employee::findOrFail($data['employee_id']);
        $data['allowances'] = $data['allowances'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_salary'] = $data['net_salary'] ?? (($data['gross_salary'] + $data['allowances']) - $data['deductions']);
        $data['currency_id'] = $data['currency_id'] ?? $employee->currency_id;

        PayrollEntry::create($data);

        return redirect()->route('payroll.index')->with('success', 'Payroll entry created.');
    }

    public function edit(PayrollEntry $payrollEntry)
    {
        $this->authorize('update-payroll');

        $employees = Employee::where('is_active', true)->orderBy('name')->get(['id', 'name', 'base_salary', 'currency_id']);
        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');

        return view('payroll.edit', compact('payrollEntry', 'employees', 'currencies'));
    }

    public function update(Request $request, PayrollEntry $payrollEntry)
    {
        $this->authorize('update-payroll');

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|regex:/^\d{4}-\d{2}$/',
            'gross_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'status' => 'required|in:draft,approved,paid',
            'paid_on' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $data['allowances'] = $data['allowances'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_salary'] = $data['net_salary'] ?? (($data['gross_salary'] + $data['allowances']) - $data['deductions']);

        $payrollEntry->update($data);

        return redirect()->route('payroll.index')->with('success', 'Payroll entry updated.');
    }

    public function destroy(PayrollEntry $payrollEntry)
    {
        $this->authorize('delete-payroll');

        $payrollEntry->delete();

        return redirect()->route('payroll.index')->with('success', 'Payroll entry deleted.');
    }

    public function payslip(PayrollEntry $payrollEntry)
    {
        $this->authorize('view-payroll');

        $payrollEntry->load(['employee', 'employee.currency', 'currency']);

        $svc = app(\App\Services\SettingsService::class);
        $logoPath = $svc->get('company_logo');
        $logoBase64 = null;
        if ($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
            $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($logoPath);
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $mime = match($ext) {
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

        $pdf = Pdf::loadView('payroll.payslip', compact('payrollEntry', 'company'))
            ->setPaper('a4');

        return $pdf->stream('payslip-'.$payrollEntry->employee->name.'-'.$payrollEntry->period.'.pdf');
    }
}