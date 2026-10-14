<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $form->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" class="form-control" placeholder="e.g. WH-01" value="{{ old('code', $form->code ?? '') }}">
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $form->location ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="d-block">&nbsp;</label>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="wh-active" {{ old('is_active', $form->is_active ?? true) ? 'checked' : '' }}>
                <label class="custom-control-label" for="wh-active">Active</label>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Notes</label>
            <input type="text" name="notes" class="form-control" value="{{ old('notes', $form->notes ?? '') }}">
        </div>
    </div>
</div>