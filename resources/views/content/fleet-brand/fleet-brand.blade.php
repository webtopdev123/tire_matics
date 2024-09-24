@extends('layouts/layoutMaster')

@section('title', 'Tyre Brand')

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
<script src="{{ asset('assets/js/fleet-brand/fleet-brand.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-brand/modal-add-brand.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-brand/modal-edit-brand.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/fleet-brand/modal-list-model.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/fleet-brand/modal-edit-model.js') }}?v={{ time() }}"></script>

@endsection
<style>
  .table-responsive{
    overflow-x: hidden !important;
  }
  table.table thead th{
    width: auto !important;
  }
  table.table thead th:first-child{
    width: 30px !important;
  }
  table.table thead th:last-child{
    width: 40px !important;
  }

  #fleet_brand_list table.table thead th:nth-child(3){
    width: 120px !important;
  }
</style>
@section('content')

  <div class="d-flex justify-content-between align-items-center title-head">
      <h5 class="mb-2">Master Data > Fleet Brand</h5>
  </div>

  <div class="card" id="fleet_brand_list">
      <div class="card-datatable table-responsive">
          <table id="main-table" class="datatables-main table border-top">
              <thead>
                  <tr>
                      <th style="width: 30px;">NO#</th>
                      <th>Brand Name</th>
                      <th style="width: 50px !important">Total Model</th>
                      <th style="width: 40px !important;">Actions</th>
                  </tr>
              </thead>
          </table>
      </div>
  </div>


@include('_partials/_modals/fleet-brand/modal-add-brand')
@include('_partials/_modals/fleet-brand/modal-edit-brand')
@include('_partials/_modals/fleet-brand/modal-list-model')
@include('_partials/_modals/fleet-brand/modal-edit-model')

@endsection
