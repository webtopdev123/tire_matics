@extends('layouts/layoutMaster')

@section('title', 'Slideshow')

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
    <script src="{{ asset('assets/js/slideshow/app-slideshow.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/slideshow/modal-add-slideshow.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/slideshow/modal-edit-slideshow.js') }}?v={{ time() }}"></script>
@endsection

@section('content')
    <style>
      td {
        vertical-align: top;
      }
    </style>
    
    <div class="justify-content-between align-items-center">
        <div class="row title-head justify-content-left align-items-center">
            <div class="" style="padding-right:30px;width:auto;">
                <h4 class="mb-2">Slideshow</h4>
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
                        <th width="150px">Date Time</th>
                        <th width="160px">Image</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ Category Table -->

    <!-- Modal -->
    @include('_partials/_modals/slideshow/modal-add-slideshow')
    @include('_partials/_modals/slideshow/modal-edit-slideshow')
    <!-- /Modal -->
@endsection
