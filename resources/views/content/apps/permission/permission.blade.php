@extends('layouts/layoutMaster')

@section('title', 'Permission')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}?v={{ time() }}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}?v={{ time() }}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}?v={{ time() }}"></script>
<script src="{{asset('assets/vendor/libs/toastr/toastr.js') }}?v={{ time() }}"></script>

<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}?v={{ time() }}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/permission/app-permission.js') }}?v={{ time() }}"></script>
<script src="{{asset('assets/js/permission/modal-add-permission.js') }}?v={{ time() }}"></script>
<script src="{{asset('assets/js/permission/modal-edit-permission.js') }}?v={{ time() }}"></script>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center title-head">
  <h4 class="mb-2">Setting > Permission</h4>
</div>

<!-- Permission Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-permissions table border-top">
      <thead>
        <tr>
          <th style="width:30px;">NO#</th>
          <th>Name</th>
          <th>Assigned To</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<!--/ Permission Table -->

<!-- Modal -->
@include('_partials/_modals/permission/modal-add-permission')
@include('_partials/_modals/permission/modal-edit-permission')
<!-- /Modal -->
@endsection
