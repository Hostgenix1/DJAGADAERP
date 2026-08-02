@extends('layouts.app')

@section('title', 'Taxes')

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Taxes</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#taxModal"><i class="fas fa-plus"></i> Add Tax</button>
        </div>
    </div>
    <div class="card-body">
        <table id="dt-taxes" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 40px">#</th>
                <th>Name</th>
                <th>Rate</th>
                <th>Kind</th>
                <th>Status</th>
                <th style="width: 100px">Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

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
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function () {
        $('#dt-taxes').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.settings.taxes.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'rate', name: 'rate' },
                { data: 'kind', name: 'kind' },
                { data: 'is_active', name: 'is_active' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']]
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