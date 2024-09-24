@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Tyre Brand')

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
<script src="{{ asset('assets/js/tyre-brand/tyre-brand.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/tyre-brand/modal-add-brand.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/tyre-brand/modal-edit-brand.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/tyre-brand/modal-list-model.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/tyre-brand/modal-edit-model.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/tyre-brand/modal-list-size.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/tyre-brand/modal-edit-size.js') }}?v={{ time() }}"></script>

@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center title-head mb-2">
        <h4>Tyre Brand</h4>
        <div class="col-auto">
            <button id="btn-create" class="btn btn-primary me-1" type="button">Create</button>
        </div>
    </div>

    <div class="card p-2" id="tyre_brand_list">
        <div class="card-datatable table-responsive">
            <table id="main-table" class="datatables table">
                <thead class="border-top">
                    <tr>
                        <th class="text-uppercase" style="min-width:40px;max-width:40px;">NO#</th>
                        <th class="text-uppercase white-space-nowrap">Brand Name</th>
                        <th class="text-uppercase text-center" style="min-width:150px;width:150px;">Total Model</th>
                        <th class="text-uppercase text-center" style="width:150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@include('_partials/_modals/tyre-brand/modal-add-brand')
@include('_partials/_modals/tyre-brand/modal-edit-brand')
@include('_partials/_modals/tyre-brand/modal-list-model')
@include('_partials/_modals/tyre-brand/modal-edit-model')
@include('_partials/_modals/tyre-brand/modal-list-size')
@include('_partials/_modals/tyre-brand/modal-edit-size')

@endsection
