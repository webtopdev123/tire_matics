@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Setting - Merchant List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}?v={{ time() }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}?v={{ time() }}"></script>
    <script src="{{asset('assets/vendor/libs/toastr/toastr.js') }}?v={{ time() }}"></script>

    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}"></script>
@endsection

@section('page-script')
  <script src="{{ asset('assets/js/profile/app-profile.js') }}?v={{ time() }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center title-head">
        <h4 class="mb-2">Content</h4>
    </div>

    <!-- Role Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables table">
                <thead class="border-top">
                    <tr>
                      <th></th>
                      <th></th>
                      <th>NO#</th>
                      <th>Name</th>
                      <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ Role Table -->

    <!-- Edit Role Modal -->
    @include('_partials/_modals/profile/modal-edit-profile')
    <!-- / Edit Role Modal -->
@endsection
