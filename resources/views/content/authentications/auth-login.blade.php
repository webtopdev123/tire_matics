@php
    $customizerHidden = 'customizer-hide';
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login')

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
    <style>
        .image-container {
            width: 97%;
            /* Adjust as needed */
            height: 97%;
            /* Adjust as needed */
            border-radius: 0.5rem;
            /* Adjust as needed */
            background-image: url('{{ asset('assets/img/illustrations/auth-login-illustation.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}?v={{ time() }}">
    </script>
    <script
        src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}?v={{ time() }}">
    </script>
    <script
        src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}?v={{ time() }}">
    </script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}?v={{ time() }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/pages-auth.js') }}?v={{ time() }}"></script>

    @if (Session::has('message'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ Session::get('message') }}',
                icon: 'error',
                confirmButtonText: 'Close',
                didOpen: () => {
                    $('.swal2-deny').remove();
                    $('.swal2-cancel').remove();
                }
            })
        </script>
    @endif
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover authentication-bg ">
        <div class="authentication-inner row">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <div class="image-container">
                    </div>
                </div>
            </div>
            <!-- /Left Text -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    {{-- <div class="app-brand mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros', [
                                'height' => 20,
                                'withbg' => 'fill: #fff;',
                            ])</span>
                        </a>
                    </div> --}}
                    <!-- /Logo -->
                    <h3 class=" mb-1">Welcome to Tirematics!</h3>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    <form id="formAuthentication" class="mb-3" action="{{ route('auth-login') }}" method="POST">
                        @csrf
                        <!-- Add this line to include CSRF protection -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Username</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter your Username" autofocus>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <!-- <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember_me">
                                <label class="form-check-label" for="remember-me">
                                    Remember Me
                                </label>
                            </div>
                        </div> -->
                        <button class="btn btn-primary d-grid w-100" type="submit">
                            Sign in
                        </button>
                    </form>

                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
@endsection
