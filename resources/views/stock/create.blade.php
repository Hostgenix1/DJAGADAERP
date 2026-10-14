@extends('layouts.app')

@section('title', 'Record Stock Movement')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-exchange-alt mr-1"></i> Record Stock Movement</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock.movements.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product *</label>
                                <select name="product_id" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    @foreach($products as $id => $name)
                                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Warehouse *</label>
                                <select name="warehouse_id" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    @foreach($warehouses as $id => $name)
                                        <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type *</label>
                                <select name="type" class="form-control" required>
                                    <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>In (received / opening)</option>
                                    <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>Out (sold / issued)</option>
                                    <option value="adjustment" {{ old('type') === 'adjustment' ? 'selected' : '' }}>Adjustment (+/- correction)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quantity *</label>
                                <input type="number" step="0.01" name="qty" class="form-control" value="{{ old('qty') }}" required>
                                <small class="text-muted">Out &amp; In take positive numbers; Adjustment may be negative.</small>
                                @error('qty')<span class="text-danger small d-block">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unit Cost</label>
                                <input type="number" step="0.01" min="0" name="unit_cost" class="form-control" value="{{ old('unit_cost') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Movement Date *</label>
                                <input type="date" name="movement_date" class="form-control" value="{{ old('movement_date', date('Y-m-d')) }}" required>
                                @error('movement_date')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Reference</label>
                                <input type="text" name="reference" class="form-control" placeholder="e.g. PO-2026-0001, INV-..." value="{{ old('reference') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Notes</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save Movement</button>
                        <a href="{{ route('stock.movements') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
