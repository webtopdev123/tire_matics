@extends('layouts/layoutMaster')

@section('title', 'Fleet Goods')

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
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/fleet-good/fleet-good.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-good/modal-add-type.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-good/modal-edit-type.js') }}?v={{ time() }}"></script>
@endsection

@section('content')

  <div class="d-flex justify-content-between align-items-center title-head">
      <h5 class="mb-2">Master Data > Fleet Goods</h5>
      <button id="btn-create" class="btn btn-primary mb-2 create-btn">Create</button>
  </div>

  <div class="card" id="fleet_brand_list">
      <div class="card-datatable table-responsive">
          <table id="main-table" class="datatables-main table border-top" style="width: 100%;">
              <thead>
                  <tr>
                        <th class="align-left text-uppercase white-space-nowrap" style="width: 30px;">NO#</th>
                        <th class="text-uppercase white-space-nowrap">Goods Name</th>
                        <th class="text-uppercase text-center white-space-nowrap" style="width: 40px;">Actions</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
  </div>


@include('_partials/_modals/fleet-good/modal-add-type')
@include('_partials/_modals/fleet-good/modal-edit-type')

@endsection
