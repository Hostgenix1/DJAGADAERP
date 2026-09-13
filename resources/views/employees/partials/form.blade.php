<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $form->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $form->email ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $form->phone ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="{{ old('department', $form->department ?? '') }}" placeholder="e.g. Sales, Operations">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Position</label>
            <input type="text" name="position" class="form-control" value="{{ old('position', $form->position ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Hire Date</label>
            <input type="date" name="hire_date" data-allow-past="1" class="form-control" value="{{ old('hire_date', ($form ?? null)?->hire_date?->format('Y-m-d') ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Base Salary *</label>
            <input type="number" step="0.01" min="0" name="base_salary" class="form-control" value="{{ old('base_salary', $form->base_salary ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Transport Allowance (monthly default)</label>
            <input type="number" step="0.01" min="0" name="transport_allowance" class="form-control" value="{{ old('transport_allowance', $form->transport_allowance ?? 0) }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Bank IBAN (for salary transfer file)</label>
            <input type="text" name="iban" class="form-control" maxlength="34" placeholder="e.g. AE070331234567890123456" value="{{ old('iban', $form->iban ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Portal Login (user account)</label>
            <select name="user_id" class="form-control">
                <option value="">-- No portal access --</option>
                @foreach($users as $id => $uname)
                    <option value="{{ $id }}" {{ old('user_id', $form->user_id ?? '') == $id ? 'selected' : '' }}>{{ $uname }}</option>
                @endforeach
            </select>
            <small class="text-muted">Linked user can view/download only their own payslips.</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Currency</label>
            <select name="currency_id" class="form-control">
                <option value="">-- Select --</option>
                @foreach($currencies as $id => $code)
                    <option value="{{ $id }}" {{ old('currency_id', $form->currency_id ?? '') == $id ? 'selected' : '' }}>{{ $code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" {{ old('is_active', $form->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>