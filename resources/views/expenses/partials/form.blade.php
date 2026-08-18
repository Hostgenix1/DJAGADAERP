<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Expense Date *</label>
            <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', ($form ?? null)?->expense_date?->format('Y-m-d') ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Amount *</label>
            <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', $form->amount ?? '') }}" required>
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
        <div class="form-group">
            <label>Category *</label>
            <select name="category" class="form-control" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category', $form->category ?? 'operating') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Paid To</label>
            <input type="text" name="paid_to" class="form-control" value="{{ old('paid_to', $form->paid_to ?? '') }}" placeholder="e.g. Rent owner, bank">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" class="form-control" value="{{ old('description', $form->description ?? '') }}" placeholder="e.g. Warehouse rent October">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $form->notes ?? '') }}</textarea>
        </div>
    </div>
</div>