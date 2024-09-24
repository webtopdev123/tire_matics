@extends('layouts/layoutMaster')

@section('title', 'Inventory')

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
<script src="{{ asset('assets/js/fleet-tyre/fleet-tyre.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-tyre/modal-list-log.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-tyre/modal-add-tyre.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-tyre/model-edit-tyre.js') }}?v={{ time() }}"></script>
@endsection

@section('content')

<div class="d-flex align-items-center title-head">
    <h5 class="mb-2 me-auto">Inventory List</h5>
    <div class="mb-1 px-1 pull-right" style="width:150px;">
      <select id="filter-type" class="form-select" name="filter_type">
        <option value="">- All Type -</option>
        @foreach ($tyreType as $key => $type)
        <option value="{{ $key }}">{{ $type }}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-1 px-1 pull-right" style="width:150px;">
      <select id="filter-status" class="form-select" name="filter_status">
        <option value="">- All Status -</option>
        @foreach ($tyreStatus as $key => $status)
        <option value="{{ $key }}">{{ $status }}</option>
        @endforeach
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
                    <th class="text-start ">Tyre</th>
                    <th class="text-start ">Details</th>
                    <th class="text-center " style="width:150px">Type</th>
                    <th class="text-center " style="width:150px">Status</th>
                    <th class="text-center" style="width:80px">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('_partials/_modals/fleet-tyre/modal-list-log')
@include('_partials/_modals/fleet-tyre/modal-add-tyre')
@include('_partials/_modals/fleet-tyre/modal-edit-tyre')

@endsection
