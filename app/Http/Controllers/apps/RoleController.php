<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\PermissionTraits;

class RoleController extends Controller
{
  use PermissionTraits;

  public function index()
  {
    $this->permissionAccess('setting-role', 'permission_role_read');

    if(Auth::user()->merchant_id === 0) {
      return redirect()->route('merchant');
    }

    return view('content.apps.role.index');
  }

  public function getRolesList(Request $request)
  {
    $this->permissionAccess('setting-role', 'permission_role_read');

    $draw = $request->input('draw');
    $start = $request->input('start');
    $length = $request->input('length');
    $order = $request->input('order');

    $userData = $request->input('user_data');

    // Default order by column and direction
    $orderBy = 'role_id';
    $orderDir = 'asc';

    if (!empty($order) && is_array($order) && count($order) > 0) {
      $columnIndex = $order[0]['column'];
      $columns = $request->input('columns');
      $columnName = $columns[$columnIndex]['data'];
      $orderDir = $order[0]['dir'];

      // You can map the column name to the corresponding database column if needed
      switch ($columnName) {
        case 'role_name':
          $orderBy = 'role_name';
          break;
        case 'users_count':
          $orderBy = 'users_count';
          break;
        default:
          $orderBy = 'role_id'; // Default order column
          break;
      }
    }

    // $userRoleId = Auth::user()->role_id;
    $search = $request->input('search.value');

    $query =
      Role::where('merchant_id', Auth::user()->merchant_id)
      ->when(!empty($search), function ($query) use ($search) {
        return $query->where('role_name', 'like', '%' . $search . '%');
      })
      ->orderBy($orderBy, $orderDir)
      ->withCount('users')
      ->with([
        'permissions' => function ($query) {
          $query->select([
            'role_id',
            'permission_id',
            'permission_role_read',
            'permission_role_create',
            'permission_role_update',
            'permission_role_delete',
          ]);
        },
      ])
      ->get();

    // Count the total records without filters
    $totalRecords=0;

    if(Auth::user()->merchant_id !== 0){
      $totalRecords = Role::where('merchant_id', Auth::user()->merchant_id)
                      ->count();
    }
    else{
      $totalRecords = Role::count();
    }

    // Count the total records with applied filters
    $filteredRecords = $query->count();

    // Apply pagination
    $query->skip($start)->take($length);

    // Prepare the response
    $response = [
      'draw' => $draw,
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $filteredRecords,
      'data' => $query,
    ];

    return response()->json($response);
  }

  public function addRole(Request $request)
  {
    $this->permissionAccess('setting-role', 'permission_role_create');

    $request->validate([
      'role_name' => 'required|string',
    ]);

    return DB::transaction(function () use ($request) {
      $userData = $request->input('user_data');
      $roleName = $request->input('role_name');
      $merchantId = $userData['merchant_id'];

      // save role and get id
      $role = Role::create([
        'role_name' => $roleName,
        'merchant_id' => $merchantId,
      ]);

      $roleId = $role->role_id;

      // loop on all selected permissions checkbox
      $selectedPermissions = $request->input('permissions');

      if ($selectedPermissions != null) {
        foreach ($selectedPermissions as $permissionId => $selectedCheckboxes) {
          if (
            isset($selectedCheckboxes['read']) ||
            isset($selectedCheckboxes['create']) ||
            isset($selectedCheckboxes['update']) ||
            isset($selectedCheckboxes['delete'])
          ) {
            PermissionRole::create([
              'permission_id' => $permissionId,
              'role_id' => $roleId,
              'permission_role_read' => isset($selectedCheckboxes['read']) ? 1 : 0,
              'permission_role_create' => isset($selectedCheckboxes['create']) ? 1 : 0,
              'permission_role_update' => isset($selectedCheckboxes['update']) ? 1 : 0,
              'permission_role_delete' => isset($selectedCheckboxes['delete']) ? 1 : 0,
            ]);
          }
        }
      }

      return response()->json(['message' => 'Roles created successfully']);
    });
  }

  public function editRole(Request $request)
  {
    $this->permissionAccess('setting-role', 'permission_role_update');

    // Validate the request data
    $request->validate([
      'role_name' => 'required|string',
    ]);

    return DB::transaction(function () use ($request) {
      // Update Roles
      $memberRole = Role::find($request->role_id);
      $memberRole->role_name = $request->role_name;
      $memberRole->save();

      $selectedPermissions = $request->input('permissions');
      $role_id = $request->role_id;

      // delete permission row if all uncheck
      $permissionIds = [];
      if (!empty($selectedPermissions)) {
        $permissionIds = array_keys($selectedPermissions);
      }
      $existingPermissions = PermissionRole::where('role_id', $role_id)
        ->pluck('permission_id')
        ->toArray();
      $permissionsToDelete = array_diff($existingPermissions, $permissionIds);
      if (!empty($permissionsToDelete)) {
        PermissionRole::where('role_id', $role_id)
          ->whereIn('permission_id', $permissionsToDelete)
          ->delete();
      }

      if (!is_null($selectedPermissions)) {
        foreach ($selectedPermissions as $permissionId => $selectedCheckboxes) {
          if (
            isset($selectedCheckboxes['read']) ||
            isset($selectedCheckboxes['create']) ||
            isset($selectedCheckboxes['update']) ||
            isset($selectedCheckboxes['delete'])
          ) {
            $data = [
              'permission_role_read' => isset($selectedCheckboxes['read']) ? 1 : 0,
              'permission_role_create' => isset($selectedCheckboxes['create']) ? 1 : 0,
              'permission_role_update' => isset($selectedCheckboxes['update']) ? 1 : 0,
              'permission_role_delete' => isset($selectedCheckboxes['delete']) ? 1 : 0,
            ];

            PermissionRole::updateOrCreate(['role_id' => $role_id, 'permission_id' => $permissionId], $data);
          }
        }
      }

      return response()->json(['message' => 'Roles updated successfully']);
    });
  }

  public function deleteRole(Request $request)
  {
    $this->permissionAccess('setting-role', 'permission_role_delete');

    $roleId = $request->input('role_id');

    if (!$roleId) {
      return response()->json(['message' => 'Role ID not provided'], 400);
    }

    $role = Role::find($roleId);

    if (!$role) {
      return response()->json(['message' => 'Role not found'], 404);
    }

    $exists = User::where('role_id', $roleId)->exists();

    if (!$exists) {
      $role->permissions()->delete();
      $role->delete();

      return response()->json(['message' => 'Role deleted successfully']);
    } else {
      return response()->json(['message' => 'Role is currently assigned to users and cannot be deleted'], 400);
    }
  }
}
