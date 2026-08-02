@php
    $categories = ['contract', 'certificate', 'invoice', 'packing_list', 'shipping', 'import', 'export', 'other'];
@endphp

<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-paperclip mr-1"></i> Documents</h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#upload-{{ $morphType ?? 'generic' }}">
                <i class="fas fa-upload"></i> Upload
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead><tr><th>Title</th><th>Category</th><th>Size</th><th>Ver</th><th>Date</th><th></th></tr></thead>
            <tbody>
            @forelse($documents as $doc)
                <tr>
                    <td>{{ $doc->title }}</td>
                    <td><span class="badge badge-light">{{ ucfirst(str_replace('_', ' ', $doc->category)) }}</span></td>
                    <td><small>{{ $doc->formatted_size }}</small></td>
                    <td><small>v{{ $doc->version }}</small></td>
                    <td><small>{{ $doc->created_at?->format('Y-m-d') }}</small></td>
                    <td>
                        <a href="{{ route('documents.download', $doc) }}" class="btn btn-xs btn-outline-success"><i class="fas fa-download"></i></a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">No documents uploaded yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="upload-{{ $morphType ?? 'generic' }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="documentable_type" value="{{ $morphClass }}">
                <input type="hidden" name="documentable_id" value="{{ $entity->id }}">
                <div class="modal-header"><h5 class="modal-title">Upload Document</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <div class="modal-body">
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
