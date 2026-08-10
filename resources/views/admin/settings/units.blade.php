@extends('layouts.app')

@section('title', 'Units')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Units of Measure</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#unitModal">
                    <i class="fas fa-plus mr-1"></i> Add Unit
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">All Units</h3>
                </div>
                <div class="card-body">
                    <table id="dt-units" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-outline card-info">
                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-info-circle mr-1"></i> How it works</h5></div>
                <div class="card-body small">
                    <p class="mb-1">Units added here appear automatically in the <strong>Unit</strong> dropdown of:</p>
                    <ul class="mb-2">
                        <li>Quotations</li>
                        <li>Invoices (Proforma / Commercial)</li>
                        <li>Sales Orders</li>
                        <li>Purchase Orders</li>
                        <li>Supplier Bills</li>
                        <li>Products</li>
                    </ul>
                    <p class="mb-0 text-muted">The selected unit is printed on the PDF as e.g. <code>USD 18.50 / Carton</code>.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD UNIT MODAL --}}
    <div class="modal fade" id="unitModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.settings.units.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Unit</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. MT, KG, Carton, Bag, Container">
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

    {{-- EDIT UNIT MODAL --}}
    <div class="modal fade" id="unitEditModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="" id="unitEditForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Unit</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="unitEditName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" id="unitEditSort" class="form-control" min="0">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="unitEditActive" value="1" class="form-check-input">
                            <label class="form-check-label" for="unitEditActive">Active</label>
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
    $('#dt-units').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.settings.units.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'is_active', name: 'is_active' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[2, 'asc'], [1, 'asc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });

    $(document).on('click', '.js-edit-unit', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $('#unitEditForm').attr('action', '/admin/settings/units/' + $btn.data('id'));
        $('#unitEditName').val($btn.data('name'));
        $('#unitEditSort').val($btn.data('sort') || 0);
        $('#unitEditActive').prop('checked', $btn.data('active') == 1);
        $('#unitEditModal').modal('show');
    });
});
</script>
@endpush
