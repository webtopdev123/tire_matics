<?php

namespace App\Traits;

trait PermissionTraits
{
    // Define your reusable methods here
    public function permissionAccess($permission_code, $method)
    {
        $permissions = request('user_data.permissions');

        foreach ($permissions as $permission) {
            if (isset($permission->permission_code)) {
                if ($permission->permission_code == $permission_code && property_exists($permission, $method) && $permission->{$method} == 1) {
                    return true;
                }
            }
        }

        return abort(404);
    }

    public function hasPermission($permission_code, $method)
    {
        $permissions = request('user_data.permissions');

        foreach ($permissions as $permission) {
            if (isset($permission->permission_code)) {
                if ($permission->permission_code == $permission_code && property_exists($permission, $method) && $permission->{$method} == 1) {
                    return true;
                }
            }
        }

        return false;
    }
}
