@extends('layouts.app')

@section('title', 'Document Management')

@section('content')
<div class="module-index">

    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                <i class="fas fa-folder-open mr-2 text-primary"></i>All Documents
            </h5>
            <div class="d-flex align-items-center gap-2">
                @can('create-documents')
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-upload mr-1"></i> Upload Document
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-top-right-radius:0; border-bottom-right-radius:0;"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="global-search" class="form-control" placeholder="Search documents by title, filename, or notes..." style="border-top-left-radius:0; border-bottom-left-radius:0;">
                    </div>
                    <div id="search-results" class="list-group mt-1" style="display:none; position:relative; z-index:10;"></div>
                </div>
                <div class="col-md-4 d-flex justify-content-end align-items-start">
                    <form method="GET" class="form-inline">
                        <select name="category" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $cat)) }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" style="width:100%">
                    <thead>
                        <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                            <th style="width:60px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Title</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Category</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">File</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Size</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Version</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Entity</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Uploaded</th>
                            <th style="width:140px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($documents as $i => $doc)
                        <tr style="transition: background .15s;">
                            <td>{{ $documents->firstItem() + $i }}</td>
                            <td style="vertical-align:middle;">{{ $doc->title }}</td>
                            <td style="vertical-align:middle;"><span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $doc->category)) }}</span></td>
                            <td style="vertical-align:middle;"><small class="text-muted">{{ $doc->original_name }}</small></td>
                            <td style="vertical-align:middle;">{{ $doc->formatted_size }}</td>
                            <td style="vertical-align:middle;">v{{ $doc->version }}</td>
                            <td style="vertical-align:middle;"><small>{{ class_basename($doc->documentable_type) }} #{{ $doc->documentable_id }}</small></td>
                            <td style="vertical-align:middle;"><small>{{ $doc->created_at?->format('Y-m-d H:i') }}</small></td>
                            <td style="vertical-align:middle;">
                                <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-outline-success" title="Download"><i class="fas fa-download"></i></a>
                                @can('delete-documents')
                                    <form action="{{ route('documents.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this document?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No documents found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($documents->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $documents->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border:none; border-radius:12px; overflow:hidden;">
            <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="background:#f8f9fa; border-bottom:1px solid #f0f0f0;">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-upload mr-2 text-primary"></i>Upload Document
                    </h5>
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
                <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .module-index .card { border: none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px; }
    .module-index .table thead th { white-space: nowrap; }
    .module-index .table tbody tr:hover { background: #f0f4ff !important; }
    #search-results { box-shadow: 0 4px 12px rgba(0,0,0,.1); border-radius: 8px; overflow: hidden; }
    #search-results .list-group-item { border-left: none; border-right: none; padding: .6rem 1rem; }
    #search-results .list-group-item:first-child { border-top: none; }
    #search-results .list-group-item:last-child { border-bottom: none; }
</style>
@endpush

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
