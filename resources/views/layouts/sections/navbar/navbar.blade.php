@php
    $containerNav = isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact' ? 'container-xxl' : 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
    $currentRouteName = Route::currentRouteName();
@endphp

@if ($currentRouteName !== 'receipt-print')

    <!-- Navbar -->
    @if (isset($navbarDetached) && $navbarDetached == 'navbar-detached' && $currentRouteName !== 'receipt-print')
        <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
            id="layout-navbar">
    @endif
    @if (isset($navbarDetached) && $navbarDetached == '')
        <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="{{ $containerNav }}">
    @endif

    <!--  Brand demo (display only for navbar-full and hide on below xl) -->
    @if (isset($navbarFull))
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                    @include('_partials.macros', ['height' => 20])
                </span>
                <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="ti ti-x ti-sm align-middle"></i>
            </a>
        </div>
    @endif

    <!-- ! Not required for layout-without-menu -->
    @if (!isset($navbarHideToggle))
        <div
            class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-sm"></i>
            </a>
        </div>
    @endif

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav flex-row align-items-center">
            <h6 class="mb-0"><b>{{ strtoupper(Auth::user()->merchant->merchant_name ?? '') }}</b></h6>
        </div>
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            @if (!empty($arrayMerchant))
                <!-- Merchant -->
                <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <i class='ti ti-building-warehouse rounded-circle ti-md'></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="merchantSwitch">
                        @if (request('user_data.user_id') == 1 || session('user_userId') == 1)
                            <li>
                                <a class="dropdown-item {{ Auth::user()->merchant_id == 0 ? 'active' : '' }}"
                                    href="{{ route('merchant.switch', ['merchant_id' => 0]) }}"
                                    data-merchant="{{ 0 }}">
                                    <span class="align-middle">All</span>
                                </a>
                            </li>
                        @endif

                        @foreach ($arrayMerchant as $merchant)
                            <li>
                                @php
                                    $activeClass = Auth::user()->merchant_id == $merchant->merchant_id ? 'active' : '';
                                @endphp
                                <a class="dropdown-item {{ $activeClass }}"
                                    href="{{ route('merchant.switch', ['merchant_id' => $merchant->merchant_id]) }}"
                                    data-merchant="{{ $merchant->merchant_id }}">
                                    <span class="align-middle">{{ $merchant->merchant_name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <!--/ Merchant -->
            @endif

            @if ($configData['hasCustomizer'] == true)
                <!-- Style Switcher -->
                <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <i class='ti ti-md'></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                <span class="align-middle"><i class='ti ti-sun me-2'></i>Light</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ Style Switcher -->
            @endif

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ Auth::user() ? asset('assets/img/avatars/empty.png') : asset('assets/img/avatars/empty.png') }}"
                            alt class="h-auto rounded-circle">
                        {{-- <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle"> --}}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user() ? asset('assets/img/avatars/empty.png') : asset('assets/img/avatars/empty.png') }}"
                                            alt class="h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">
                                    @if (session('current_switched_userRoleName'))
                                        {{ session('current_switched_userRoleName') }}
                                    @else
                                        @if (Auth::check())
                                            {{ Auth::user()->name }}
                                        @else
                                            -
                                        @endif
                                    @endif
                                    </span>
                                    <small class="text-muted">
                                        @if (Auth::check())
                                            {{ Auth::user()->role->role_name }}
                                        @else
                                            Empty
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    @if (Auth::check() && Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <li>
                            <a class="dropdown-item" href="{{ route('api-tokens.index') }}">
                                <i class='ti ti-key me-2 ti-sm'></i>
                                <span class="align-middle">API Tokens</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::User() && Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <h6 class="dropdown-header">Manage Team</h6>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="{{ Auth::user() ? route('teams.show', Auth::user()->currentTeam->id) : 'javascript:void(0)' }}">
                                <i class='ti ti-settings me-2'></i>
                                <span class="align-middle">Team Settings</span>
                            </a>
                        </li>
                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <li>
                                <a class="dropdown-item" href="{{ route('teams.create') }}">
                                    <i class='ti ti-user me-2'></i>
                                    <span class="align-middle">Create New Team</span>
                                </a>
                            </li>
                        @endcan
                        @if (Auth::user()->allTeams()->count() > 1)
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li>
                                <h6 class="dropdown-header">Switch Teams</h6>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                        @endif
                        @if (Auth::user())
                            @foreach (Auth::user()->allTeams() as $team)
                                {{-- Below commented code read by artisan command while installing jetstream. !! Do not remove if you want to use jetstream. --}}

                                {{-- <x-switchable-team :team="$team" /> --}}
                            @endforeach
                        @endif
                    @endif
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    @if (Auth::check())
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class='ti ti-logout me-2'></i>
                                <span class="align-middle">Logout</span>
                            </a>
                        </li>
                        <form method="POST" id="logout-form" action="{{ route('logout') }}">
                            @csrf
                        </form>
                    @else
                        <li>
                            <a class="dropdown-item"
                                href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                                <i class='ti ti-login me-2'></i>
                                <span class="align-middle">Login</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
        <input type="text"
            class="form-control search-input {{ isset($menuHorizontal) ? '' : $containerNav }} border-0"
            placeholder="Search..." aria-label="Search...">
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
    @if (isset($navbarDetached) && $navbarDetached == '')
        </div>
    @endif
    </nav>

@endif
<!-- / Navbar -->
<style>
    #toast-container>.toast {
        padding: 15px 30px 15px 50px !important;
    }
</style>
