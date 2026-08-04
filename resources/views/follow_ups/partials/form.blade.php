<div class="row">
    <div class="col-md-6 col-field">
        <label for="type" class="form-label">Type *</label>
        <select name="type" id="type" class="form-control" required>
            <option value="">-- Select Type --</option>
            @foreach(['call', 'email', 'meeting', 'task'] as $t)
                <option value="{{ $t }}" {{ old('type', $form->type ?? '') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        @error('type')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="due_date" class="form-label">Due Date *</label>
        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', optional($form)->due_date?->format('Y-m-d') ?? '') }}" required>
        @error('due_date')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="assigned_to" class="form-label">Assigned To</label>
        <select name="assigned_to" id="assigned_to" class="form-control">
            <option value="">-- Select User --</option>
            @foreach($relations['assigned_to'] as $id => $name)
                <option value="{{ $id }}" {{ old('assigned_to', optional($form)->assigned_to ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('assigned_to')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="completed_at" class="form-label">Completed At</label>
        <input type="datetime-local" name="completed_at" id="completed_at" class="form-control" value="{{ old('completed_at', optional($form)->completed_at?->format('Y-m-d\TH:i') ?? '') }}">
        @error('completed_at')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-12 col-field">
        <label for="followable_type" class="form-label">Link To Type</label>
        <select name="followable_type" id="followable_type" class="form-control">
            <option value="">-- None --</option>
            <option value="App\Models\Customer" {{ old('followable_type', optional($form)->followable_type ?? '') === 'App\Models\Customer' ? 'selected' : '' }}>Customer</option>
            <option value="App\Models\Lead" {{ old('followable_type', optional($form)->followable_type ?? '') === 'App\Models\Lead' ? 'selected' : '' }}>Lead</option>
        </select>
    </div>
    <div class="col-md-12 col-field">
        <label for="followable_id" class="form-label">Related To *</label>
        <select name="followable_id" id="followable_id" class="form-control" required>
            <option value="">-- Select --</option>
            <optgroup label="Customers">
                @foreach(\App\Models\Customer::where('is_active', true)->orderBy('company_name')->get() as $c)
                    <option value="{{ $c->id }}" data-type="App\Models\Customer" {{ old('followable_id', optional($form)->followable_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->company_name }}</option>
                @endforeach
            </optgroup>
            <optgroup label="Leads">
                @foreach(\App\Models\Lead::orderBy('company_name')->get() as $l)
                    <option value="{{ $l->id }}" data-type="App\Models\Lead" {{ old('followable_id', optional($form)->followable_id ?? '') == $l->id ? 'selected' : '' }}>{{ $l->company_name }}</option>
                @endforeach
            </optgroup>
        </select>
        @error('followable_id')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-12 col-field">
        <label for="note" class="form-label">Note</label>
        <textarea name="note" id="note" class="form-control" rows="3">{{ old('note', optional($form)->note ?? '') }}</textarea>
        @error('note')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
</div>
<script>
$(function() {
    $('#followable_id').on('change', function() {
        var type = $(this).find(':selected').data('type');
        if (type) $('#followable_type').val(type).trigger('change');
    });
});
</script>
