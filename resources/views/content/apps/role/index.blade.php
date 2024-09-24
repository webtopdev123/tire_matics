@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Role')

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
    <script src="{{asset('assets/js/role/role.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/role/modal-add-role.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/role/modal-edit-role.js') }}?v={{ time() }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center title-head">
        <h4 class="mb-2">Setting > User Role</h4>
    </div>

    <!-- Role cards -->
    <div class="row g-4">
        <div class="col-12">
            <!-- Role Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="datatables-roles table border-top">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>NO#</th>
                                <th>Role Name</th>
                                <th>Total User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--/ Role Table -->
        </div>
    </div>
    <!--/ Role cards -->

    <!-- Add Role Modal -->
    @include('_partials/_modals/role/modal-add-role')
    <!-- / Add Role Modal -->

    <!-- Edit Role Modal -->
    @include('_partials/_modals/role/modal-edit-role')
    <!-- / Edit Role Modal -->

<style>

@media (max-width: 767px) {
  .max-height-350 {
    max-height: 350px;
  }
}

</style>    
@endsection
