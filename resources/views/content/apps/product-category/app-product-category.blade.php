@extends('layouts/layoutMaster')

@section('title', 'Product')

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
    <script src="{{ asset('assets/js/product-category/app-product-category.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/product-category/modal-add-product-category.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/product-category/modal-edit-product-category.js') }}?v={{ time() }}"></script>
@endsection

@section('content')
    <div class="justify-content-between align-items-center">
        <div class="row title-head justify-content-left align-items-center">
            <div class="" style="padding-right:30px;width:auto;">
                <h4 class="mb-2">Product > Product Category</h4>
            </div>
        </div>
    </div>

    <!-- Category Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-level table border-top">
                <thead>
                    <tr>
                        <th>NO#</th>
                        <th width="70px">Icon</th>
                        <th>Category Name</th>
                        <th class="text-center" style="min-width:170px;">SKU</th>
                        <th class="text-center" style="min-width:170px;">Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ Category Table -->

    <!-- Modal -->
    @include('_partials/_modals/product-category/modal-add-product-category')
    @include('_partials/_modals/product-category/modal-edit-product-category')
    <!-- /Modal -->
@endsection
