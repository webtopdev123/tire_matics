@extends('layouts/layoutMaster')

@section('title', 'Fleet')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}?v={{ time() }}" />
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}?v={{ time() }}"></script>
<script
    src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}"></script>
<script
    src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/fleet/fleet.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet/modal-add-fleet.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet/modal-edit-fleet.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/fleet/modal-list-axle.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet/modal-edit-axle.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/fleet/modal-list-tyre.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet/modal-edit-tyre.js') }}?v={{ time() }}"></script>
@endsection

@section('content')

<div class="d-flex align-items-center title-head">
    <h5 class="mb-2 me-auto">Fleet List</h5>
    <div class="mb-1 px-1 pull-right" style="width:150px;">
      <select class="select2 form-control" id="filter-status" style="text-align:center;width:100%;">
        <option value="1" selected>Active</option>
        <option value="0">Inactive</option>
      </select>
    </div>
    <button id="btn-create" class="btn btn-primary mb-2 create-btn">Create</button>
</div>

<div class="card" id="fleet_brand_list">
    <div class="card-datatable table-responsive">
        <table id="main-table" class="datatables-main table border-top" style="width: 100%;">
            <thead>
                <tr>
                    <th class="text-center " style="width:30px">NO#</th>
                    <th class="text-start ">FLEET</th>
                    <th class="text-start ">Details</th>
                    <th class="text-center " style="width:150px">Status</th>
                    <th class="text-center" style="width:80px">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


@include('_partials/_modals/fleet/modal-add-fleet')
@include('_partials/_modals/fleet/modal-edit-fleet')
@include('_partials/_modals/fleet/modal-list-axle')
@include('_partials/_modals/fleet/modal-edit-axle')
@include('_partials/_modals/fleet/modal-list-tyre')
@include('_partials/_modals/fleet/modal-edit-tyre')

@endsection
