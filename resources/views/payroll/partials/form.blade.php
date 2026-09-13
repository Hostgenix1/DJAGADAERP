<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Employee *</label>
            <select name="employee_id" id="payroll-employee" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" data-salary="{{ $emp->base_salary }}" data-transport="{{ $emp->transport_allowance ?? 0 }}" data-currency="{{ $emp->currency_id }}"
                        {{ old('employee_id', $form->employee_id ?? '') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Period *</label>
            <input type="month" name="period" id="payroll-period" class="form-control" value="{{ old('period', $form->period ?? date('Y-m')) }}" required>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>&nbsp;</label>
            <button type="button" id="payroll-autofill" class="btn btn-info btn-block" title="Load attendance + salary math"><i class="fas fa-magic"></i> Auto-fill</button>
        </div>
    </div>
</div>

<div class="card card-outline card-info">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Attendance Summary <small class="text-muted">(auto from records, editable)</small></h3></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Working Days (÷)</label>
                    <input type="number" min="1" max="31" name="working_days" id="f-working" class="form-control calc" value="{{ old('working_days', $form->working_days ?? 30) }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Present Days</label>
                    <input type="number" step="0.5" min="0" name="present_days" id="f-present" class="form-control" value="{{ old('present_days', $form->present_days ?? 0) }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Absent Days (unpaid)</label>
                    <input type="number" step="0.5" min="0" name="absent_days" id="f-absent" class="form-control calc" value="{{ old('absent_days', $form->absent_days ?? 0) }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Leave Days (paid)</label>
                    <input type="number" step="0.5" min="0" name="leave_days" id="f-leave" class="form-control" value="{{ old('leave_days', $form->leave_days ?? 0) }}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-success">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-money-bill-wave mr-1"></i> Salary Breakdown</h3></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Base Salary *</label>
                    <input type="number" step="0.01" min="0" name="base_salary" id="f-base" class="form-control calc" value="{{ old('base_salary', $form->base_salary ?? $form->gross_salary ?? '') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Transport Allowance</label>
                    <input type="number" step="0.01" min="0" name="transport_allowance" id="f-transport" class="form-control calc" value="{{ old('transport_allowance', $form->transport_allowance ?? 0) }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Bonus</label>
                    <input type="number" step="0.01" min="0" name="bonus" id="f-bonus" class="form-control calc" value="{{ old('bonus', $form->bonus ?? 0) }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Other Allowances</label>
                    <input type="number" step="0.01" min="0" name="other_allowances" id="f-oallow" class="form-control calc" value="{{ old('other_allowances', $form->other_allowances ?? 0) }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Absence Deduction <small class="text-muted">(auto)</small></label>
                    <input type="number" step="0.01" min="0" name="absence_deduction" id="f-absded" class="form-control calc" value="{{ old('absence_deduction', $form->absence_deduction ?? 0) }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Other Deductions</label>
                    <input type="number" step="0.01" min="0" name="other_deductions" id="f-odeduc" class="form-control calc" value="{{ old('other_deductions', $form->other_deductions ?? 0) }}">
                </div>
            </div>
        </div>
        <div class="alert alert-secondary mb-0">
            Per-day rate: <strong id="pv-perday">0.00</strong> &nbsp;•&nbsp;
            Allowances: <strong id="pv-allow">0.00</strong> &nbsp;•&nbsp;
            Deductions: <strong id="pv-deduc">0.00</strong> &nbsp;•&nbsp;
            Net payable: <strong id="pv-net" class="text-success">0.00</strong>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Currency</label>
            <select name="currency_id" id="payroll-currency" class="form-control">
                <option value="">-- Select --</option>
                @foreach($currencies as $id => $code)
                    <option value="{{ $id }}" {{ old('currency_id', $form->currency_id ?? '') == $id ? 'selected' : '' }}>{{ $code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                @foreach(['draft', 'approved', 'due', 'paid'] as $st)
                    <option value="{{ $st }}" {{ old('status', $form->status ?? 'draft') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <small class="text-muted">Approve early, set Pay Date — system marks Due at 00:00.</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Pay Date (scheduled)</label>
            <input type="date" name="pay_date" class="form-control" value="{{ old('pay_date', ($form ?? null)?->pay_date?->format('Y-m-d') ?? '') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Paid On</label>
            <input type="date" name="paid_on" class="form-control" value="{{ old('paid_on', ($form ?? null)?->paid_on?->format('Y-m-d') ?? '') }}">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Notes</label>
            <input type="text" name="notes" class="form-control" value="{{ old('notes', $form->notes ?? '') }}">
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function(){
    const n = v => parseFloat(v) || 0;
    function preview(fromAuto){
        const base = n($('#f-base').val());
        const wd = parseInt($('#f-working').val()) || 30;
        const absent = n($('#f-absent').val());
        // Mirror server math exactly: per-day rounded FIRST, then absence deduction.
        const perDay = Math.round((wd > 0 ? base / wd : 0) * 100) / 100;
        if (fromAuto) { $('#f-absded').val((Math.round(perDay * absent * 100) / 100).toFixed(2)); }
        const allow = n($('#f-transport').val()) + n($('#f-bonus').val()) + n($('#f-oallow').val());
        const deduc = n($('#f-absded').val()) + n($('#f-odeduc').val());
        $('#pv-perday').text(perDay.toFixed(2));
        $('#pv-allow').text(allow.toFixed(2));
        $('#pv-deduc').text(deduc.toFixed(2));
        $('#pv-net').text((base + allow - deduc).toFixed(2));
    }
    $('.calc').on('input', function(){ preview(false); });
    $('#payroll-employee').on('change', function(){
        const o = $(this).find(':selected');
        if (o.val()) {
            if (o.data('salary') !== undefined) { $('#f-base').val(o.data('salary')); }
            if (o.data('transport') !== undefined) { $('#f-transport').val(o.data('transport')); }
            if (o.data('currency')) { $('#payroll-currency').val(o.data('currency')); }
        }
        preview(true);
    });
    $('#payroll-autofill').on('click', function(){
        const emp = $('#payroll-employee').val(), period = $('#payroll-period').val();
        if (!emp || !period) { alert('Select employee and period first.'); return; }
        const btn = $(this).prop('disabled', true);
        $.getJSON('{{ route("payroll.autofill") }}', { employee_id: emp, period: period })
            .done(function(d){
                $('#f-base').val(d.base_salary);
                $('#f-working').val(d.working_days);
                $('#f-present').val(d.present_days);
                $('#f-absent').val(d.absent_days);
                $('#f-leave').val(d.leave_days);
                $('#f-transport').val(d.transport_allowance);
                $('#f-bonus').val(d.bonus);
                $('#f-oallow').val(d.other_allowances);
                $('#f-absded').val(d.absence_deduction);
                $('#f-odeduc').val(d.other_deductions);
                if (d.currency_id) { $('#payroll-currency').val(d.currency_id); }
                preview(false);
            })
            .fail(function(xhr){ alert('Auto-fill failed: ' + (xhr.responseJSON?.message || xhr.statusText)); })
            .always(function(){ btn.prop('disabled', false); });
    });
    preview(true);
});
</script>
@endpush
