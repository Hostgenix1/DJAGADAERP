@extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-warehouse mr-1"></i> Edit Warehouse</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('warehouses.update', $warehouse) }}">
                    @csrf
                    @method('PUT')
                    @include('warehouses.partials.form', ['form' => $warehouse])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="{{ route('warehouses.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection