<ul class="menu-sub">
    @if (isset($menu))
        @foreach ($menu as $submenu)
            {{-- active menu method --}}
            @php
                $activeClass = null;
                $active = $configData['layout'] === 'vertical' ? 'active open' : 'active';
                $currentRouteName = Route::currentRouteName();
                $displayMenuPermission = true;

                if (!empty($submenu->permission_code)) {
                    $permissionCode = $submenu->permission_code;

                    if (!array_key_exists($permissionCode, $permissionMap) || $permissionMap[$permissionCode]->permission_role_read != 1) {
                        $displayMenuPermission = false;
                    }
                }

                if ($currentRouteName === $submenu->slug) {
                    $activeClass = 'active';
                } elseif (isset($submenu->submenu)) {
                    if (gettype($submenu->slug) === 'array') {
                        foreach ($submenu->slug as $slug) {
                            if (($currentRouteName == $slug) and strpos($currentRouteName, $slug) === 0) {
                                $activeClass = $active;
                            }
                        }
                    } else {
                        if (($currentRouteName == $submenu->slug) and strpos($currentRouteName, $submenu->slug) === 0) {
                            $activeClass = $active;
                        }
                    }
                } elseif (!isset($submenu->submenu) and gettype($submenu->slug) === 'array') {
                    foreach ($submenu->slug as $slug) {
                        if (($currentRouteName == $slug) and strpos($currentRouteName, $slug) === 0) {
                            $activeClass = $active;
                        }
                    }
                }
                // dd($submenu->slug);
            @endphp
            @if (($submenu->slug == 'merchant.index' and request('user_data.merchant_id') == 0) || $submenu->slug !== 'merchant.index')
                @if ($displayMenuPermission)
                    <li class="menu-item {{ $activeClass }}">
                        <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}"
                            class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                            @if (isset($submenu->target) and !empty($submenu->target)) target="_blank" @endif>
                            @if (isset($submenu->icon))
                                <i class="{{ $submenu->icon }}"></i>
                            @endif
                            <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
                        </a>

                        {{-- submenu --}}
                        @if (isset($submenu->submenu))
                            @include('layouts.sections.menu.submenu', ['menu' => $submenu->submenu])
                        @endif
                    </li>
                @endif
            @endif
        @endforeach
    @endif
</ul>
