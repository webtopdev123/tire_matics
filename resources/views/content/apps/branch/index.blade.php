@extends('layouts/layoutMaster')

@section('title', 'Branch')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}?v={{ time() }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}?v={{ time() }}">
    </script>
    <script
        src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}">
    </script>
    <script
        src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}">
    </script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}?v={{ time() }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/branch/app-branch.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/branch/modal-add-branch.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/branch/modal-edit-branch.js') }}?v={{ time() }}"></script>
@endsection

@section('content')
    <div class="justify-content-between align-items-center">
        <div class="row title-head justify-content-left align-items-center">
            <div class="" style="padding-right:30px;width:auto;">
                <h4 class="mb-2">Branch</h4>
            </div>
        </div>
    </div>

    <!-- Category Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-level table border-top">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>NO#</th>
                        <th>Branch Address</th>
                        <th>Branch Phone</th>
                        <th>Branch Main</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ Category Table -->

    <!-- Modal -->
    @include('_partials/_modals/branch/modal-add-branch')
    @include('_partials/_modals/branch/modal-edit-branch')
    <!-- /Modal -->
@endsection
