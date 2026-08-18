<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Employee *</label>
            <select name="employee_id" id="payroll-employee" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" data-salary="{{ $emp->base_salary }}" data-currency="{{ $emp->currency_id }}"
                        {{ old('employee_id', $form->employee_id ?? '') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Period *</label>
            <input type="month" name="period" class="form-control" value="{{ old('period', $form->period ?? date('Y-m')) }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Gross Salary *</label>
            <input type="number" step="0.01" min="0" name="gross_salary" id="payroll-gross" class="form-control" value="{{ old('gross_salary', $form->gross_salary ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Allowances</label>
            <input type="number" step="0.01" min="0" name="allowances" id="payroll-allow" class="form-control" value="{{ old('allowances', $form->allowances ?? 0) }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Deductions</label>
            <input type="number" step="0.01" min="0" name="deductions" id="payroll-deduc" class="form-control" value="{{ old('deductions', $form->deductions ?? 0) }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Net Salary</label>
            <input type="number" step="0.01" min="0" name="net_salary" id="payroll-net" class="form-control" value="{{ old('net_salary', $form->net_salary ?? '') }}" placeholder="Auto-computed">
        </div>
    </div>
    <div class="col-md-4">
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
    <div class="col-md-4">
        <div class="form-group">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                @foreach(['draft', 'approved', 'paid'] as $st)
                    <option value="{{ $st }}" {{ old('status', $form->status ?? 'draft') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Paid On</label>
            <input type="date" name="paid_on" class="form-control" value="{{ old('paid_on', ($form ?? null)?->paid_on?->format('Y-m-d') ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Notes</label>
            <input type="text" name="notes" class="form-control" value="{{ old('notes', $form->notes ?? '') }}">
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function(){
    function autoNet(){
        const g = parseFloat($('#payroll-gross').val()) || 0;
        const a = parseFloat($('#payroll-allow').val()) || 0;
        const d = parseFloat($('#payroll-deduc').val()) || 0;
        if (!$('#payroll-net').val()) {
            $('#payroll-net').val((g + a - d).toFixed(2));
        }
    }
    $('#payroll-employee').on('change', function(){
        const o = $(this).find(':selected');
        if (o.data('salary') !== undefined) { $('#payroll-gross').val(o.data('salary')); }
        if (o.data('currency')) { $('#payroll-currency').val(o.data('currency')); }
        $('#payroll-net').val('');
        autoNet();
    });
    $('#payroll-gross, #payroll-allow, #payroll-deduc').on('input', function(){
        $('#payroll-net').val('');
        autoNet();
    });
});
</script>
@endpush