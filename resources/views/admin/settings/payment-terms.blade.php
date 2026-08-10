@extends('layouts.app')

@section('title', 'Payment Terms')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Payment Terms</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#termModal">
                    <i class="fas fa-plus mr-1"></i> Add Payment Term
                </button>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Document Defaults</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.payment_terms.defaults') }}">
                @csrf
                <div class="row">
                    @foreach($docTypes as $key => $label)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ $label }}</label>
                                <select name="default_pt_{{ $key }}" class="form-control">
                                    <option value="">-- No default --</option>
                                    @foreach(\App\Support\PaymentTerms::all() as $pt)
                                        <option value="{{ $pt }}" {{ $defaults[$key] === $pt ? 'selected' : '' }}>{{ $pt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <label>Incoterms list (comma separated)</label>
                    <input type="text" name="incoterms_list" class="form-control" value="{{ $defaults['incoterms_list'] ?? '' }}" placeholder="EXW, FOB, CFR, CIF, DAP, DDP">
                    <small class="text-muted">These appear in the Delivery Terms dropdown of quotes, invoices and purchase orders. You can always type a custom incoterm in any document.</small>
                </div>
                <button class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i> Save Defaults</button>
            </form>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">All Payment Terms</h3>
        </div>
        <div class="card-body">
            <table id="dt-terms" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Sort Order</th>
                        <th>Default</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- ADD TERM MODAL --}}
    <div class="modal fade" id="termModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.settings.payment_terms.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Payment Term</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. 30% advance + 70% before shipment">
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT TERM MODAL --}}
    <div class="modal fade" id="termEditModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="" id="termEditForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment Term</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="termEditName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" id="termEditSort" class="form-control" min="0">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_default" id="termEditDefault" value="1" class="form-check-input">
                            <label class="form-check-label" for="termEditDefault">Set as global default</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="termEditActive" value="1" class="form-check-input">
                            <label class="form-check-label" for="termEditActive">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#dt-terms').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.settings.payment_terms.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'is_default', name: 'is_default' },
            { data: 'is_active', name: 'is_active' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[2, 'asc'], [1, 'asc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });

    $(document).on('click', '.js-edit-term', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $('#termEditForm').attr('action', '/admin/settings/payment-terms/' + $btn.data('id'));
        $('#termEditName').val($btn.data('name'));
        $('#termEditSort').val($btn.data('sort') || 0);
        $('#termEditDefault').prop('checked', $btn.data('default') == 1);
        $('#termEditActive').prop('checked', $btn.data('active') == 1);
        $('#termEditModal').modal('show');
    });
});
</script>
@endpush
