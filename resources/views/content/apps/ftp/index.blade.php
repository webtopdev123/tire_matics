@extends('layouts/layoutMaster')

@section('title', 'FTP')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}?v={{ time() }}"></script>

    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}?v={{ time() }}">
    </script>
    <script
        src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}">
    </script>
    <script
        src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}">
    </script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/ftp/app-ftp.js') }}?v={{ time() }}"></script>
@endsection

@section('content')

<div style="display:flex;justify-content:space-between;" class="flex-sm-row flex-column">
  <h4 class="mb-2 col-sm-4 col-12">FTP</h4>
  <div class="title-head col-md-3 col-12 justify-content-left justify-content-md-end" style="display:flex;">
    <div class="col-6 col-md-8 ">
      
    </div>
  </div>
</div>

<div id="progress-bar-container" class="progress" style="display:none;">
  <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
</div>

    <!-- Permission Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-level table border-top">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>NO#</th>
                        <th width="70px">Picture</th>
                        <th>Picture Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--/ Permission Table -->


    <style>
        .select2-container--open {
            z-index: 9999999999;
        }

        .table-sm> :not(caption)>*>* {
            padding: 0.25rem 0.25rem !important;
        }

        .invalid-custom-input {
            border-color: red;
        }

        @media (max-width: 576px) {

            #variants-table,
            #edit-variants-table {
                display: block;
            }
            .datatables-level table{
              margin-left:15px !important;
            }
        }
    </style>

@endsection
