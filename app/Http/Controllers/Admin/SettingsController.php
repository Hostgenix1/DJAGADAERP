<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    public function __construct(protected SettingsService $settings)
    {
    }

    /* ------------------------------------------------------------------ */
    /* company information                                                 */
    /* ------------------------------------------------------------------ */

    public function company()
    {
        $this->authorize('view-settings');

        return view('admin.settings.company');
    }

    public function updateCompany(Request $request)
    {
        $this->authorize('update-settings');

        $request->validate([
            'company_name' => ['required', 'string', 'max:200'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_city' => ['nullable', 'string', 'max:150'],
            'company_state' => ['nullable', 'string', 'max:150'],
            'company_postal' => ['nullable', 'string', 'max:20'],
            'company_country' => ['nullable', 'string', 'max:150'],
            'company_website' => ['nullable', 'url', 'max:255'],
            'company_industry' => ['nullable', 'string', 'max:100'],
            'company_smtp_host' => ['nullable', 'string', 'max:255'],
            'company_smtp_port' => ['nullable', 'string', 'max:10'],
            'company_smtp_username' => ['nullable', 'string', 'max:255'],
            'company_smtp_password' => ['nullable', 'string', 'max:255'],
            'company_smtp_from_name' => ['nullable', 'string', 'max:255'],
            'company_smtp_from_email' => ['nullable', 'email', 'max:255'],
            'company_smtp_encryption' => ['nullable', 'string'],
            'company_logo' => ['nullable'],
            'show_logo_on_docs' => ['nullable', 'boolean'],
            'company_footer_text' => ['nullable', 'string', 'max:500'],
            'company_notes' => ['nullable', 'string', 'max:1000'],
            'company_terms' => ['nullable', 'string', 'max:1000'],

            'company_trade_license' => ['nullable', 'string', 'max:100'],
            'company_trn' => ['nullable', 'string', 'max:15'],
            'company_free_zone' => ['nullable', 'string', 'max:100'],
            'company_entity_type' => ['nullable', 'string', 'max:50'],
            'default_currency_id' => ['nullable', 'exists:currencies,id'],
            'remove_logo' => ['nullable', 'in:0,1'],
        ]);

        $data = $request->only([
            'company_name', 'company_email', 'company_phone',
            'company_address', 'company_city', 'company_state', 'company_postal', 'company_country',
            'company_website', 'company_industry',
            'company_smtp_host', 'company_smtp_port', 'company_smtp_username', 'company_smtp_password',
            'company_smtp_from_name', 'company_smtp_from_email',

            'company_trade_license', 'company_trn', 'company_free_zone', 'company_entity_type',
            'default_currency_id',
        ]);

        $data['company_smtp_encryption'] = $request->boolean('company_smtp_encryption') ? 'tls' : 'none';
        $data['show_logo_on_docs'] = $request->boolean('show_logo_on_docs') ? 1 : 0;
        $data['company_footer_text'] = $request->input('company_footer_text');
        $data['company_notes'] = $request->input('company_notes');
        $data['company_terms'] = $request->input('company_terms');

        if ($request->boolean('remove_logo')) {
            $old = $this->settings->get('company_logo');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $data['company_logo'] = null;
        }

        if ($request->hasFile('company_logo')) {
            $request->validate([
                'company_logo' => ['image', 'max:2048'],
            ]);
            $old = $this->settings->get('company_logo');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $data['company_logo'] = $request->file('company_logo')->store('logos', 'public');
        }

        $this->settings->bulkSet($data);

        if ($request->filled('default_currency_id')) {
            \App\Models\Currency::query()->update(['is_default' => false]);
            \App\Models\Currency::where('id', $request->input('default_currency_id'))->update(['is_default' => true]);
        }

        activity()->causedBy(auth()->user())->event('updated')->log('updated company settings');

        return redirect()->route('admin.settings.company')->with('success', 'Company settings saved.');
    }

    /* ------------------------------------------------------------------ */
    /* taxes                                                               */
    /* ------------------------------------------------------------------ */

    public function taxes()
    {
        $this->authorize('view-settings');

        return view('admin.settings.taxes');
    }

    public function taxesDatatable()
    {
        $this->authorize('view-settings');

        return DataTables::eloquent(Tax::query())
            ->addIndexColumn()
            ->addColumn('rate', fn (Tax $tax) => number_format((float) $tax->rate, 3).'%')
            ->addColumn('is_active', fn (Tax $tax) => $tax->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>')
            ->addColumn('actions', fn (Tax $tax) => view('admin.settings.partials.tax-actions', ['row' => $tax])->render())
            ->rawColumns(['is_active', 'actions'])
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->make(true);
    }

    public function taxStore(Request $request)
    {
        $this->authorize('update-settings');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'kind' => ['required', 'in:sales,purchase'],
        ]);

        $tax = Tax::create($data);

        activity()->causedBy(auth()->user())->performedOn($tax)->event('created')->log('created');

        return back()->with('success', 'Tax added.');
    }

    public function taxUpdate(Request $request, Tax $tax)
    {
        $this->authorize('update-settings');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'kind' => ['required', 'in:sales,purchase'],
            'is_active' => ['boolean'],
        ]);

        $tax->update($data);

        activity()->causedBy(auth()->user())->performedOn($tax)->event('updated')->log('updated');

        return back()->with('success', 'Tax updated.');
    }

    public function taxDestroy(Tax $tax)
    {
        $this->authorize('update-settings');

        $tax->delete();

        activity()->causedBy(auth()->user())->event('deleted')->log('deleted tax');

        return back()->with('success', 'Tax deleted.');
    }

    /* ------------------------------------------------------------------ */
    /* audit log                                                           */
    /* ------------------------------------------------------------------ */

    public function audit()
    {
        $this->authorize('view-settings');

        return view('admin.settings.audit');
    }

    public function auditDatatable()
    {
        $this->authorize('view-settings');

        return DataTables::eloquent(Activity::with('causer')->latest('id'))
            ->addIndexColumn()
            ->addColumn('user', fn (Activity $a) => $a->causer?->email ?? '-')
            ->addColumn('event', fn (Activity $a) => '<span class="badge bg-info">'.$a->event.'</span>')
            ->addColumn('subject', fn (Activity $a) => $a->log_name)
            ->addColumn('description', fn (Activity $a) => $a->description)
            ->editColumn('created_at', fn (Activity $a) => $a->created_at?->format('d M Y H:i'))
            ->rawColumns(['event'])
            ->make(true);
    }
}
