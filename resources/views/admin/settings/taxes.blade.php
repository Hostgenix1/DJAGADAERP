@extends('layouts.app')

@section('title', 'Taxes')

@section('content')
<div class="module-index">

    {{-- MAIN TABLE CARD --}}
    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                <i class="fas fa-percent mr-2 text-primary"></i>All Taxes
            </h5>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#taxModal">
                    <i class="fas fa-plus mr-1"></i> Add Tax
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="dt-taxes" class="table table-hover" style="width:100%">
                    <thead>
                        <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                            <th style="width:60px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Name</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Rate</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Kind</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Status</th>
                            <th style="width:140px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ADD TAX MODAL --}}
    <div class="modal fade" id="taxModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.settings.taxes.store') }}">
                    @csrf
                    <div class="modal-header"><h5 class="modal-title">New Tax</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Rate (%)</label>
                            <input type="number" step="0.001" min="0" max="100" name="rate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kind</label>
                            <select name="kind" class="form-control">
                                <option value="sales">Sales</option>
                                <option value="purchase">Purchase</option>
                            </select>
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

    {{-- EDIT TAX MODAL --}}
    <div class="modal fade" id="taxEditModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="" id="taxEditForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><h5 class="modal-title">Edit Tax</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="taxEditName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Rate (%)</label>
                            <input type="number" step="0.001" min="0" max="100" name="rate" id="taxEditRate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kind</label>
                            <select name="kind" id="taxEditKind" class="form-control">
                                <option value="sales">Sales</option>
                                <option value="purchase">Purchase</option>
                            </select>
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
<style>
    .module-index .card { border: none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px; }
    #dt-taxes thead th { white-space: nowrap; }
    #dt-taxes tbody tr { transition: background .15s; }
    #dt-taxes tbody tr:hover { background: #f0f4ff !important; }
    #dt-taxes td { vertical-align: middle; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#dt-taxes').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.settings.taxes.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'rate', name: 'rate' },
            { data: 'kind', name: 'kind' },
            { data: 'is_active', name: 'is_active' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });

    $(document).on('click', '.js-edit-tax', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $('#taxEditForm').attr('action', '/admin/settings/taxes/' + $btn.data('id'));
        $('#taxEditName').val($btn.data('name'));
        $('#taxEditRate').val($btn.data('rate'));
        $('#taxEditKind').val($btn.data('kind'));
        $('#taxEditModal').modal('show');
    });
});
</script>
@endpush
