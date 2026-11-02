@extends('layouts.app')
@section('title', 'Product: '.$product->name)
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-box mr-1"></i> {{ $product->name }}</h3>
                <div class="card-tools">
                    <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>SKU:</strong> {{ $product->sku }}<br>
                        <strong>Brand:</strong> {{ $product->brand?->name ?: '-' }}<br>
                        <strong>Category:</strong> {{ $product->category?->name ?: '-' }}<br>
                        <strong>Supplier:</strong> {{ $product->supplier?->company_name ?: '-' }}
                    </div>
                    <div class="col-md-6">
@php $baseCode = \App\Support\CurrencyHelper::baseCurrency()?->code ?: ''; @endphp
                        <strong>Buy Price:</strong> {{ $product->buy_price ? $baseCode.' '.number_format($product->buy_price, 2) : '-' }}<br>
                        <strong>Sell Price:</strong> {{ $product->sell_price ? $baseCode.' '.number_format($product->sell_price, 2) : '-' }}<br>
                        <strong>Tax:</strong> {{ $product->tax?->name ? $product->tax->name.' ('.$product->tax->rate.'%)' : '-' }}
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Unit:</strong> {{ $product->unit ?: '-' }}<br>
                        <strong>Pack Qty:</strong> {{ $product->pack_qty ?: '-' }}<br>
                        <strong>Pack Type:</strong> {{ $product->pack_type ? ucfirst($product->pack_type) : '-' }}<br>
                        <strong>Weight:</strong> {{ $product->weight_kg ? number_format($product->weight_kg, 3).' kg' : '-' }}<br>
                        <strong>Dimensions:</strong> {{ $product->dimensions ?: '-' }}
                    </div>
                </div>
                @if($product->specifications)
                    <div class="mb-3"><strong>Specifications:</strong><br>{!! nl2br(e($product->specifications)) !!}</div>
                @endif
                @if($product->certificates)
                    <div class="mb-3"><strong>Certificates:</strong><br>{!! nl2br(e($product->certificates)) !!}</div>
                @endif
                <div class="text-muted" style="font-size:0.85em;">
                    Created: {{ $product->created_at?->format('d M Y H:i') }} | Updated: {{ $product->updated_at?->format('d M Y H:i') }}
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-images mr-1"></i> Images</h3></div>
            <div class="card-body">
                @if($product->images->isEmpty())
                    <p class="text-muted mb-3">No images uploaded yet.</p>
                @else
                    <div class="row">
                        @foreach($product->images->sortByDesc('is_primary') as $image)
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card h-100 mb-0">
                                    <img src="{{ $image->url }}" alt="{{ $image->alt ?: $product->name }}"
                                         class="card-img-top" style="height:140px; object-fit:cover;">
                                    <div class="card-body p-2">
                                        @if($image->is_primary)
                                            <span class="badge badge-success">Primary</span>
                                        @endif
                                        @can('update-products')
                                            <div class="d-flex mt-2">
                                                @unless($image->is_primary)
                                                    <form method="POST" action="{{ route('products.images.primary', [$product, $image]) }}" class="mr-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-info" title="Set as primary"><i class="fas fa-star"></i></button>
                                                    </form>
                                                @endunless
                                                <form method="POST" action="{{ route('products.images.destroy', [$product, $image]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete image" onclick="return confirm('Delete this image?');"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @can('update-products')
                    <form method="POST" action="{{ route('products.images.store', $product) }}" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="product-images">Upload images</label>
                            <input type="file" name="images[]" id="product-images" class="form-control-file" accept="image/*" multiple required>
                            <small class="text-muted">JPEG, PNG or WebP, max 4MB each.</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload mr-1"></i> Upload</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Actions</h3></div>
            <div class="card-body">
                @can('update-products')
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-info btn-block"><i class="fas fa-edit"></i> Edit</a>
                @endcan
                <a href="{{ route('products.index') }}" class="btn btn-default btn-block"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
