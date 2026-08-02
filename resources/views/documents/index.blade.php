@extends('layouts.app')

@section('title', 'Document Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Documents</h1>
        </div>
        <div class="col-sm-6">
            @can('create-documents')
                <div class="float-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-upload mr-1"></i> Upload Document
                    </button>
                </div>
            @endcan
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">All Documents</h3>
            <div class="card-tools">
                <form method="GET" class="form-inline">
                    <div class="input-group input-group-sm">
                        <select name="category" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $cat)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <input type="text" id="global-search" class="form-control" placeholder="Search documents by title, filename, or notes...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    <div id="search-results" class="list-group mt-1" style="display:none;"></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>File</th>
                            <th>Size</th>
                            <th>Version</th>
                            <th>Entity</th>
                            <th>Uploaded</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($documents as $i => $doc)
                        <tr>
                            <td>{{ $documents->firstItem() + $i }}</td>
                            <td>{{ $doc->title }}</td>
                            <td><span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $doc->category)) }}</span></td>
                            <td><small class="text-muted">{{ $doc->original_name }}</small></td>
                            <td>{{ $doc->formatted_size }}</td>
                            <td>v{{ $doc->version }}</td>
                            <td><small>{{ class_basename($doc->documentable_type) }} #{{ $doc->documentable_id }}</small></td>
                            <td><small>{{ $doc->created_at?->format('Y-m-d H:i') }}</small></td>
                            <td>
                                <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-success" title="Download"><i class="fas fa-download"></i></a>
                                @can('delete-documents')
                                    <form action="{{ route('documents.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this document?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">No documents found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($documents->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $documents->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Entity Type</label>
                        <select name="documentable_type" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="App\Models\Customer">Customer</option>
                            <option value="App\Models\Lead">Lead</option>
                            <option value="App\Models\Product">Product</option>
                            <option value="App\Models\Quote">Quote</option>
                            <option value="App\Models\Invoice">Invoice</option>
                            <option value="App\Models\Payment">Payment</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Entity ID</label>
                        <input type="number" name="documentable_id" class="form-control" required min="1">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ ucfirst(str_replace('_', ' ', $cat)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>File (max 20MB)</label>
                        <input type="file" name="file" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        let timer;
        $('#global-search').on('keyup', function () {
            clearTimeout(timer);
            const q = $(this).val();
            if (q.length < 2) { $('#search-results').hide().empty(); return; }
            timer = setTimeout(() => {
                $.get('{{ route("documents.search") }}', { q }, function (data) {
                    const $r = $('#search-results').empty();
                    if (!data.length) { $r.hide(); return; }
                    data.forEach(d => {
                        $r.append(`<a href="${d.url}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file mr-2"></i><strong>${d.title}</strong>
                            <small class="text-muted ml-2">${d.entity_type} #${d.entity_id}</small>
                        </a>`);
                    });
                    $r.show();
                });
            }, 300);
        });
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#global-search, #search-results').length) {
                $('#search-results').hide();
            }
        });
    });
</script>
@endpush
