<div class="row">
    <div class="col-md-6 col-field">
        <label for="type" class="form-label">Type *</label>
        <select name="type" id="type" class="form-control" required>
            <option value="">-- Select Type --</option>
            @foreach(['call', 'whatsapp', 'email', 'meeting', 'note'] as $t)
                <option value="{{ $t }}" {{ old('type', optional($form)->type ?? '') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        @error('type')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="direction" class="form-label">Direction *</label>
        <select name="direction" id="direction" class="form-control" required>
            <option value="">-- Select Direction --</option>
            @foreach(['outbound', 'inbound'] as $d)
                <option value="{{ $d }}" {{ old('direction', optional($form)->direction ?? '') === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
            @endforeach
        </select>
        @error('direction')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="subject" class="form-label">Subject</label>
        <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', optional($form)->subject ?? '') }}">
        @error('subject')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="occurred_at" class="form-label">Date/Time *</label>
        <input type="datetime-local" name="occurred_at" id="occurred_at" class="form-control" value="{{ old('occurred_at', optional($form)->occurred_at?->format('Y-m-d\TH:i') ?? date('Y-m-d\TH:i')) }}" required>
        @error('occurred_at')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="contact_id" class="form-label">Contact</label>
        <select name="contact_id" id="contact_id" class="form-control">
            <option value="">-- None --</option>
            @foreach($relations['contact_id'] as $id => $name)
                <option value="{{ $id }}" {{ old('contact_id', optional($form)->contact_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('contact_id')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="user_id" class="form-label">User</label>
        <select name="user_id" id="user_id" class="form-control">
            <option value="">-- Select User --</option>
            @foreach($relations['user_id'] as $id => $name)
                <option value="{{ $id }}" {{ old('user_id', optional($form)->user_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('user_id')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-12 col-field">
        <label for="communicable_type" class="form-label">Link To Type</label>
        <select name="communicable_type" id="communicable_type" class="form-control">
            <option value="">-- None --</option>
            <option value="App\Models\Customer" {{ old('communicable_type', optional($form)->communicable_type ?? '') === 'App\Models\Customer' ? 'selected' : '' }}>Customer</option>
            <option value="App\Models\Lead" {{ old('communicable_type', optional($form)->communicable_type ?? '') === 'App\Models\Lead' ? 'selected' : '' }}>Lead</option>
        </select>
    </div>
    <div class="col-md-12 col-field">
        <label for="body" class="form-label">Body</label>
        <textarea name="body" id="body" class="form-control" rows="4">{{ old('body', optional($form)->body ?? '') }}</textarea>
        @error('body')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>
</div>
