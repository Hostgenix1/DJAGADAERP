<div class="row">
<div class="col-md-6 col-field">
            <label for="name" class="form-label">Name *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $form->name ?? '') }}">
            @error('name')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $form->slug ?? '') }}">
            @error('slug')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-12 col-field">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $form->description ?? '') }}</textarea>
            @error('description')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
</div>
