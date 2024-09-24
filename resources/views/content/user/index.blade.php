@extends('layouts/layoutMaster')

@section('title', 'User List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}?v={{ time() }}" />

@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}?v={{ time() }}">
    </script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}?v={{ time() }}">
    </script>
    <script
        src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}">
    </script>
    <script
        src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}">
    </script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}?v={{ time() }}"></script>
@endsection

@section('page-script')
    <script>
        const userAuthId = {{ Auth::user()->id }};
    </script>
    <script src="{{ asset('assets/js/user/user.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/user/modal-add-user.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/user/modal-edit-user.js') }}?v={{ time() }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center title-head">
        <h4 class="mb-2">Setting > User List</h4>
    </div>

    <!-- Users List Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>NO#</th>
                        <th>User</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Add User Modal -->
    @include('_partials/_modals/user/modal-add-user')
    <!-- / Add User Modal -->

    <!-- Edit User Modal -->
    @include('_partials/_modals/user/modal-edit-user')
    <!-- / Edit User Modal -->

@endsection
