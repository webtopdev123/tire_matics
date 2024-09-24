@php
    $configData = Helper::appClasses();
@endphp

@php
    $permissions = request('user_data.permissions');
    $permissionMap = [];
    $permissionAccess = [];

    if (request('user_data.merchant_id') == 0) {
        $permissionAccess = [
            'merchant',
            'product',
            'product-category',
            "ftp",
            "fleet-brand",
            "fleet-segment",
            "fleet-configuration",
            "fleet-good",
            "fleet-category",
            "tyre-brand"
        ];
    }
    
    if ($permissions != null) {
        foreach ($permissions as $permission) {
            if (empty($permissionAccess) || in_array($permission->permission_code, $permissionAccess)) {
                $permissionMap[$permission->permission_code] = $permission;
            }
        }
    }
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                {{-- <span class="app-brand-logo demo">
                    @include('_partials.macros', ['height' => 20])
                </span> --}}
                <span class="app-brand-logo"><img src="{{ asset('assets/img/illustrations/pos_logo2.jpg') }}" alt="Logo" style="height: 42px;"></span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)
            @php
                $activeClass = null;
                $permissionCode = null;
                $displayMenuPermission = true;

                if (!empty($menu->permission_code) && is_array($menu->permission_code)) {
                    $displayMenuPermission = false; // Initialize as false

                    foreach ($menu->permission_code as $permissionCode) {
                        if (array_key_exists($permissionCode, $permissionMap) && $permissionMap[$permissionCode]->permission_role_read === 1) {
                            $displayMenuPermission = true; // Set to true if at least one condition is met
                            break; // Exit the loop early since we already found a match
                        }
                    }
                } else {
                    if (!empty($menu->permission_code)) {
                        $permissionCode = $menu->permission_code;

                        if (!array_key_exists($permissionCode, $permissionMap) || $permissionMap[$permissionCode]->permission_role_read !== 1) {
                            $displayMenuPermission = false;
                        }
                    }
                }

                $currentRouteName = Route::currentRouteName();
                // dd($currentRouteName);
                if (isset($menu->slug)) {
                    if ($currentRouteName === $menu->slug) {
                        $activeClass = 'active';
                    } elseif (isset($menu->submenu)) {
                        if (gettype($menu->slug) === 'array') {
                            foreach ($menu->slug as $slug) {
                                if (($currentRouteName == $slug) and strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        } else {
                            if (($currentRouteName == $menu->slug) and strpos($currentRouteName, $menu->slug) === 0) {
                                $activeClass = 'active open';
                            }
                        }
                    } else {
                      if (gettype($menu->slug) === 'array') {
                            foreach ($menu->slug as $slug) {
                                if (($currentRouteName == $slug) and strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active';
                                }
                            }
                        } else {
                            if (($currentRouteName == $menu->slug) and strpos($currentRouteName, $menu->slug) === 0) {
                                $activeClass = 'active';
                            }
                        }
                    }
                }
            @endphp

            @if (isset($menu->menuHeader))
                @if ($displayMenuPermission)
                    {{-- menu headers --}}
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">{{ $menu->menuHeader }}</span>
                    </li>
                @endif
            @else
                @if ($displayMenuPermission)
                  @if( ($menu->slug == 'merchant.index' and request('user_data.merchant_id') == 0) || ($menu->slug !== 'merchant.index'))
                    @if( ( is_array($menu->slug)  and in_array('fleet-brand', $menu->slug) and  request('user_data.merchant_id') == 0 ) || !is_array($menu->slug) || ! in_array('fleet-brand', $menu->slug) )
                    {{-- main menu --}}
                    <li class="menu-item {{ $activeClass }}" permission-code="{{ $permissionCode }}">
                        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                            @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                            @isset($menu->icon)
                                <i class="{{ $menu->icon }}"></i>
                            @endisset
                            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                            @isset($menu->badge)
                                <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}
                                </div>
                            @endisset
                        </a>

                        {{-- submenu --}}
                        @isset($menu->submenu)
                            @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                        @endisset
                    </li>
                    @endif
                  @endif
                @endif
            @endif
        @endforeach
    </ul>

</aside>

<script>
    var permissionMap = @json($permissionMap);
</script>
