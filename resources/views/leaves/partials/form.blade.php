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
            <label>Type *</label>
            <select name="type" class="form-control" required>
                @foreach(['annual', 'sick', 'unpaid', 'other'] as $t)
                    <option value="{{ $t }}" {{ old('type', $form->type ?? 'annual') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Start Date *</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', ($form ?? null)?->start_date?->format('Y-m-d') ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>End Date *</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', ($form ?? null)?->end_date?->format('Y-m-d') ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Days *</label>
            <input type="number" step="0.5" min="0.5" name="days" class="form-control" value="{{ old('days', $form->days ?? 1) }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                @foreach(['pending', 'approved', 'rejected'] as $st)
                    <option value="{{ $st }}" {{ old('status', $form->status ?? 'pending') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Reason</label>
            <input type="text" name="reason" class="form-control" value="{{ old('reason', $form->reason ?? '') }}">
        </div>
    </div>
</div>