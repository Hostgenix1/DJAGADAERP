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
            <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', ($form ?? null)?->hire_date?->format('Y-m-d') ?? '') }}">
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