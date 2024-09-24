<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
  public function index()
  {
    return view('content.apps.permission.permission');
  }

  // get permission list with assigned to roles
  public function getPermissionList(Request $request)
  {

    $draw = $request->input('draw');
    $start = $request->input('start');
    $length = $request->input('length');
    $order = $request->input('order');

    // Default order by column and direction
    $orderBy = 'permission_id';
    $orderDir = 'asc';

    if (!empty($order) && is_array($order) && count($order) > 0) {
      $columnIndex = $order[0]['column'];
      $columns = $request->input('columns');
      $columnName = $columns[$columnIndex]['data'];
      $orderDir = $order[0]['dir'];

      // You can map the column name to the corresponding database column if needed
      switch ($columnName) {
        case 'name':
          $orderBy = 'permission_name';
          break;
        case 'created_date':
          $orderBy = 'created_at';
          break;
        default:
          $orderBy = 'permission_id'; // Default order column
          break;
      }
    }

    $query = Permission::with('roles')
      ->select([
        'permission_id as permission_id',
        'permission_name as name',
        'permission_code as code',
        DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d, %H:%i:%s') as created_date")
      ])
      ->orderBy($orderBy, $orderDir);

    $search = $request->input('search.value');

    if (!empty($search)) {
      $query->where('permission_name', 'like', '%' . $search . '%');
    }

    // Count the total records without filters
    $totalRecords = Permission::count();

    // Count the total records with applied filters
    $filteredRecords = $query->count();

    // Apply pagination
    $query->skip($start)->take($length);

    $permissions = $query->get();

    $permissions = $permissions->map(function ($permission) {

      $permission->assigned_to = $permission->roles->pluck('role_name')->all();
      unset($permission->roles);
      return $permission;

    });

    // Prepare the response
    $response = [
      'draw' => $draw,
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $filteredRecords,
      'data' => $permissions,
    ];

    return response()->json($response);
  }

  // just get permission list without assigned to roles
  public function getPermissions(Request $request)
  {

    $arrayPermissions = [];

    $filePath = resource_path('menu/verticalMenu.json');

    if (file_exists($filePath)) {
      $jsonString = file_get_contents($filePath);

      $data = json_decode($jsonString);

      foreach ($data->menu as $menu) {
        switch (true) {
          case !empty($menu->url) && !empty($menu->permission_code):
            $permissionCode = $menu->permission_code;
            $permission = $this->fetchPermissionIfNotExist($permissionCode, $arrayPermissions);
            if ($permission) {
              $permission->name = $menu->name;
              $arrayPermissions[] = $permission;
            }
            break;
          case !empty($menu->submenu) && is_array($menu->submenu):
            foreach ($menu->submenu as $submenu) {
              if (!empty($submenu->url) && !empty($submenu->permission_code)) {
                $permissionCode = $submenu->permission_code;
                $permission = $this->fetchPermissionIfNotExist($permissionCode, $arrayPermissions);
                if ($permission) {
                  $permission->submenu_name = $menu->name;
                  $permission->submenu_sub_name = $submenu->name;
                  $arrayPermissions[] = $permission;
                }
              }
            }
            break;
          default:
            // Handle any other cases if needed
            break;
        }
      }
    }

    return response()->json($arrayPermissions);
  }

  function fetchPermissionIfNotExist($permissionCode, $arrayPermissions)
  {

    if($permissionCode == 'merchant')
      return false;

    $existingPermission = array_filter($arrayPermissions, function ($permission) use ($permissionCode) {
      return $permission->code === $permissionCode;
    });

    if ($existingPermission) {
      return null;
    }

    $permission = Permission::select([
      'permission_id as permission_id',
      'permission_name as name',
      'permission_code as code',
      'created_at as created_date',
      'permission_read',
      'permission_create',
      'permission_update',
      'permission_delete'
    ])
    ->where('permission_code', $permissionCode)
    ->first();

    if (!$permission) {
      return null;
    }

    $permissionRole = PermissionRole::where('permission_id', $permission->permission_id )
    ->where('role_id', Auth::user()->role_id)
    ->first();

    if (!$permissionRole) {
      return null;
    }

    return $permission;
    // if (Auth::user()->merchant_id !== 0) {
    //   $query->whereNot('permission_level', 'ADMIN');
    // }

    // return $query->first();
  }


  public function addPermission(Request $request)
  {

    $request->validate([
      'permission_name' => 'required|string',
      'permission_code' => 'required|string',
    ]);

    $permission = new Permission([
      'permission_name' => $request->input('permission_name'),
      'permission_code' => $request->input('permission_code'),
      'permission_read' => 1,
      'permission_create' => 1,
      'permission_update' => 1,
      'permission_delete' => 1
    ]);

    $permission->save();

    return response()->json(['message' => 'Permission created successfully', 'status' => "200"]);

  }

  public function editPermission(Request $request)
  {
    // Validate the request data
    $request->validate([
      'permission_id' => 'required',
      'permission_name' => 'required|string',
      'permission_code' => 'required|string',
    ]);

    // Update the permission
    $permission = Permission::find($request->permission_id);
    $permission->permission_name = $request->permission_name;
    $permission->permission_code = $request->permission_code;
    $permission->save();

    return response()->json(['message' => 'Permission updated successfully']);
  }

  public function deletePermission(Request $request)
  {

    $permissionId = $request->input('permission_id');

    if (!$permissionId) {
      return response()->json(['message' => 'Permission ID not provided'], 400);
    }

    $permission = Permission::find($permissionId);

    if (!$permission) {
      return response()->json(['message' => 'Permission not found'], 404);
    }

    $permission->delete();

    return response()->json(['message' => 'Permission deleted successfully']);

  }
}
