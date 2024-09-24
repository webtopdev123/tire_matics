<?php

namespace Database\Seeders;

use App\Models\PermissionRole;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPermission = [
            [
                'permission_name' => 'Menu',
                'permission_code' => 'menu',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Sale',
                'permission_code' => 'sale',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Stock',
                'permission_code' => 'stock',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Shift',
                'permission_code' => 'shift',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Cash Sale Report',
                'permission_code' => 'cash-sale-report',
                'permission_read' => 1,
                'permission_create' => 0,
                'permission_update' => 0,
                'permission_delete' => 0,
            ],
            [
                'permission_name' => 'Stock Report',
                'permission_code' => 'stock-report',
                'permission_read' => 1,
                'permission_create' => 0,
                'permission_update' => 0,
                'permission_delete' => 0,
            ],
            [
                'permission_name' => 'Merchant List',
                'permission_code' => 'merchant',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Admin List',
                'permission_code' => 'user',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Supplier List',
                'permission_code' => 'supplier',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
              'permission_name' => 'Customer List',
              'permission_code' => 'customer',
              'permission_read' => 1,
              'permission_create' => 1,
              'permission_update' => 1,
              'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Admin Role',
                'permission_code' => 'role',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Admin Permission',
                'permission_code' => 'permission',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Product List',
                'permission_code' => 'product',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Product Setting',
                'permission_code' => 'product-category',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'Product Unit',
                'permission_code' => 'unit',
                'permission_read' => 1,
                'permission_create' => 1,
                'permission_update' => 1,
                'permission_delete' => 1,
            ],
            [
                'permission_name' => 'System',
                'permission_code' => 'setting',
                'permission_read' => 1,
                'permission_create' => 0,
                'permission_update' => 1,
                'permission_delete' => 0,
            ],
        ];

        $dataPermission = array_map(function ($record) {
            $record['created_at'] = now();
            return $record;
        }, $dataPermission);

        $insertedIds = [];

        foreach ($dataPermission as $item) {
            $permission = Permission::firstOrCreate(['permission_code' => $item['permission_code']], $item);

            // Get the permission_id, whether it was created or already existed
            $insertedIds[] = $permission->permission_id;
        }

        $role = Role::firstOrCreate(['role_id' => 1], ['role_name' => 'SuperAdmin', 'merchant_id' => '1']);

        $role_id = $role->role_id;

        $dataRolePermission = [];

        foreach ($insertedIds as $insertedId) {
            $dataRolePermission[] = [
                'permission_role_read' => 1,
                'permission_role_create' => 1,
                'permission_role_update' => 1,
                'permission_role_delete' => 1,
                'role_id' => $role_id,
                'permission_id' => $insertedId,
            ];
        }

        $dataRolePermission = array_map(function ($record) {
            $record['created_at'] = now();
            return $record;
        }, $dataRolePermission);

        foreach ($dataRolePermission as $item) {
            PermissionRole::updateOrInsert(['permission_id' => $item['permission_id']], $item);
        }
    }
}
