<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Employee *</label>
            <select name="employee_id" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($employees as $id => $name)
                    <option value="{{ $id }}" {{ old('employee_id', $form->employee_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Date *</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', ($form ?? null)?->date?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Check In</label>
            <input type="time" name="check_in" class="form-control" value="{{ old('check_in', $form->check_in ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Check Out</label>
            <input type="time" name="check_out" class="form-control" value="{{ old('check_out', $form->check_out ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                @foreach(['present', 'absent', 'leave', 'half_day'] as $st)
                    <option value="{{ $st }}" {{ old('status', $form->status ?? 'present') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Notes</label>
            <input type="text" name="notes" class="form-control" value="{{ old('notes', $form->notes ?? '') }}">
        </div>
    </div>
</div>