@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-icons.css') }}" />
@endsection

@section('vendor-script')
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/merchant/merchant.js') }}?v={{ time() }}"></script> --}}
@endsection

@section('content')
    @php
        $permissions = request('user_data.permissions');
        $permissionMap = [];
        $permissionAccess = [];

        if (request('user_data.merchant_id') == 0) {
            $permissionAccess = ['merchant','product','product-category',"ftp","fleet-brand",
            "fleet-configuration",
            "fleet-category",
            "tyre-brand",
            "fleet"];
        }
        if ($permissions != null) {
            foreach ($permissions as $permission) {
                if (empty($permissionAccess) || in_array($permission->permission_code, $permissionAccess)) {
                    $permissionMap[$permission->permission_code] = $permission;
                }
            }
        }
    @endphp
    <div class="d-flex flex-wrap justify-content-left mt-2" id="icons-container">
        @foreach ($menuData[0]->menu as $menu)
            @php
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
            @endphp

            @if (isset($menu->menuHeader))
                @if ($displayMenuPermission)
                    {{-- menu headers --}}
                    {{-- <div class="d-flex flex-wrap justify-content-center" id="icons-container">
                    <h4 class="py-3 mb-2">{{ $menu->menuHeader }}</h4>
                </div> --}}
                @endif
            @else
                @if ($displayMenuPermission and ( $menu->slug == 'merchant.index' and request('user_data.merchant_id') == 0) || $menu->slug !== 'merchant.index' )
                    @isset($menu->url)
                        <a class="card icon-card cursor-pointer text-center mb-3 mx-2 nav-link"
                            href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}">
                            <div class="card-body">
                                @isset($menu->icon)
                                    @php
                                        // Explode the $menu->icon by space
                                        $iconClasses = explode(' ', $menu->icon);

                                        // Remove 'menu-icon' and 'tf-icons' classes
                                        $filteredClasses = array_filter($iconClasses, function ($class) {
                                            return $class !== 'menu-icon' && $class !== 'tf-icons';
                                        });

                                        // Reconstruct the string without the specified classes
                                        $filteredIcon = implode(' ', $filteredClasses);
                                    @endphp

                                    <i class="{{ $filteredIcon }}"></i>
                                @endisset
                                {{-- <i class="ti ti-brand-adobe mb-2"></i> --}}
                                <p class="icon-name text-capitalize mb-0">
                                    {{ isset($menu->name) ? __($menu->name) : '' }}</p>
                            </div>
                        </a>
                    @endisset

                    @isset($menu->submenu)
                        @if (isset($menu))
                            @foreach ($menu->submenu as $submenu)
                                {{-- active menu method --}}
                                @php
                                    $displayMenuPermission = true;

                                    if (!empty($submenu->permission_code)) {
                                        $permissionCode = $submenu->permission_code;

                                        if (!array_key_exists($permissionCode, $permissionMap) || $permissionMap[$permissionCode]->permission_role_read != 1) {
                                            $displayMenuPermission = false;
                                        }
                                    }
                                @endphp
                                @if (
                                    ($submenu->slug == 'product.index' and request('user_data.merchant_id') == 0) ||
                                        $submenu->slug !== 'product.index')
                                  @if( ( is_array($menu->slug) and ( in_array('product-category', $menu->slug) || in_array('product-variant.index', $menu->slug)) and  request('user_data.merchant_id') == 0 ) || !is_array($menu->slug))    
                                    @if ($displayMenuPermission)
                                        <a class="card icon-card cursor-pointer text-center mb-3 mx-2 nav-link"
                                            href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0);' }}">
                                            <div class="card-body">
                                                @isset($menu->icon)
                                                    @php
                                                        // Explode the $menu->icon by space
                                                        $iconClasses = explode(' ', $menu->icon);

                                                        // Remove 'menu-icon' and 'tf-icons' classes
                                                        $filteredClasses = array_filter($iconClasses, function ($class) {
                                                            return $class !== 'menu-icon' && $class !== 'tf-icons';
                                                        });

                                                        // Reconstruct the string without the specified classes
                                                        $filteredIcon = implode(' ', $filteredClasses);
                                                    @endphp

                                                    <i class="{{ $filteredIcon }}"></i>
                                                @endisset
                                                {{-- <i class="ti ti-brand-adobe mb-2"></i> --}}
                                                <p class="icon-name text-capitalize mb-0">
                                                    {{ isset($submenu->name) ? __($submenu->name) : '' }}</p>
                                            </div>
                                        </a>
                                    @endif
                                  @endif  
                                @endif
                            @endforeach
                        @endif
                    @endisset
                @endif
            @endif
        @endforeach
    </div>

@endsection
