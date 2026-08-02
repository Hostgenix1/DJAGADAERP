<div class="row">
<div class="col-md-6 col-field">
            <label for="company_name" class="form-label">Company Name *</label>
            <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $form->company_name ?? '') }}">
            @error('company_name')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="contact_name" class="form-label">Contact Name</label>
            <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ old('contact_name', $form->contact_name ?? '') }}">
            @error('contact_name')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $form->email ?? '') }}">
            @error('email')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $form->phone ?? '') }}">
            @error('phone')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="source" class="form-label">Source</label>
            <select name="source" id="source" class="form-control @error('source') is-invalid @enderror"><option value="">-- Select --</option><option value="website" {{ old('source', $form->source ?? '') == 'website' ? 'selected' : '' }}>website</option><option value="referral" {{ old('source', $form->source ?? '') == 'referral' ? 'selected' : '' }}>referral</option><option value="cold_call" {{ old('source', $form->source ?? '') == 'cold_call' ? 'selected' : '' }}>cold_call</option><option value="marketing" {{ old('source', $form->source ?? '') == 'marketing' ? 'selected' : '' }}>marketing</option><option value="trade_show" {{ old('source', $form->source ?? '') == 'trade_show' ? 'selected' : '' }}>trade_show</option><option value="social_media" {{ old('source', $form->source ?? '') == 'social_media' ? 'selected' : '' }}>social_media</option><option value="other" {{ old('source', $form->source ?? '') == 'other' ? 'selected' : '' }}>other</option></select>
            @error('source')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="status" class="form-label">Status *</label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"><option value="">-- Select --</option><option value="new" {{ old('status', $form->status ?? '') == 'new' ? 'selected' : '' }}>new</option><option value="contacted" {{ old('status', $form->status ?? '') == 'contacted' ? 'selected' : '' }}>contacted</option><option value="qualified" {{ old('status', $form->status ?? '') == 'qualified' ? 'selected' : '' }}>qualified</option><option value="proposal" {{ old('status', $form->status ?? '') == 'proposal' ? 'selected' : '' }}>proposal</option><option value="won" {{ old('status', $form->status ?? '') == 'won' ? 'selected' : '' }}>won</option><option value="lost" {{ old('status', $form->status ?? '') == 'lost' ? 'selected' : '' }}>lost</option></select>
            @error('status')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="expected_amount" class="form-label">Expected Amount</label>
            <input type="number" step="0.01" name="expected_amount" id="expected_amount" class="form-control" value="{{ old('expected_amount', $form->expected_amount ?? '') }}">
            @error('expected_amount')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="currency_id" class="form-label">Currency</label>
                                    <select name="currency_id" id="currency_id" class="form-control @error('currency_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['currency_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('currency_id', $form->currency_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('currency_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="expected_date" class="form-label">Expected Close Date</label>
            <input type="date" name="expected_date" id="expected_date" class="form-control" value="{{ old('expected_date', $form->expected_date ?? '') }}">
            @error('expected_date')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="owner_id" class="form-label">Owner</label>
                                    <select name="owner_id" id="owner_id" class="form-control @error('owner_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['owner_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('owner_id', $form->owner_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('owner_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="customer_id" class="form-label">Converted Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['customer_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('customer_id', $form->customer_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('customer_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-12 col-field">
            <label for="note" class="form-label">Notes</label>
            <textarea name="note" id="note" rows="3" class="form-control @error('note') is-invalid @enderror">{{ old('note', $form->note ?? '') }}</textarea>
            @error('note')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
</div>
