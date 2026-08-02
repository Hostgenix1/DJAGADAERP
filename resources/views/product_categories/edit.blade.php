@extends('layouts.app')

@section('title', 'Edit Category')

{{--
  Edit Category - Products Module
  Module: Products
  Features: Category edit form, pre-populated fields, PATCH method override, reusable form partial, validation errors
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-folder-open mr-1"></i> Edit Category</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('product_categories.update', $product_category->id) }}">
                    @csrf
                    @method("PATCH")
                    @include('product_categories.partials.form', ['form' => $product_category])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('product_categories.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
