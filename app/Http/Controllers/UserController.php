<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\PermissionTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
  use PermissionTraits;

  public function index()
  {
    $this->permissionAccess('setting-user', 'permission_role_read');

    if (Auth::user()->merchant_id === 0) {
      return redirect()->route('merchant.index');
    }

    return view('content.user.index');
  }

  public function list(Request $request)
  {
    $this->permissionAccess('setting-user', 'permission_role_read');

    $draw = $request->input('draw');
    $start = $request->input('start');
    $length = $request->input('length');
    $order = $request->input('order');

    // Default order by column and direction
    $orderBy = 'id';
    $orderDir = 'desc';

    if (!empty($order) && is_array($order) && count($order) > 0) {
      $columnIndex = $order[0]['column'];
      $columns = $request->input('columns');
      $columnName = $columns[$columnIndex]['data'];
      $orderDir = $order[0]['dir'];

      // You can map the column name to the corresponding database column if needed
      switch ($columnName) {
        case 'name':
          $orderBy = 'name';
          break;
        case 'role_name':
          $orderBy = 'role_name';
          break;
        case 'status':
          $orderBy = 'status';
          break;
        default:
          $orderBy = 'id'; // Default order column
          break;
      }
    }

    $query = User::leftJoin('nano_merchant_user_role', 'nano_merchant_user.role_id', '=', 'nano_merchant_user_role.role_id')
      ->select([
        'nano_merchant_user.id',
        'nano_merchant_user.role_id',
        'nano_merchant_user.name',
        'nano_merchant_user.status',
        'nano_merchant_user.merchant_id',
        'nano_merchant_user_role.role_name as role_name',
      ])
      ->where('nano_merchant_user.merchant_id', Auth::user()->merchant_id)
      ->where('nano_merchant_user.id', '!=', 1)
      ->orderBy($orderBy, $orderDir);

    $search = $request->input('search.value');

    if (!empty($search)) {
      $query->where('name', 'like', '%' . $search . '%');
    }


    $totalRecords = User::
                    where('merchant_id', Auth::user()->merchant_id)
                    ->where('id', '!=', 1)
                    ->count();

    // Count the total records with applied filters
    $filteredRecords = $query->count();

    // Apply pagination
    $query->skip($start)->take($length);

    $userList = $query->get();

    // Prepare the response
    $response = [
      'draw' => $draw,
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $filteredRecords,
      'data' => $userList,
    ];

    return response()->json($response);
  }

  public function addUser(Request $request)
  {
    $this->permissionAccess('setting-user', 'permission_role_create');

    try {

      $rules = [
        'name' => [
          'required',
          'string',
          Rule::unique('nano_merchant_user', 'name')->whereNull('deleted_at'),
        ],
        'password' => 'required|string',
        'role_id' => 'required|integer',
        'status' => 'required|integer',
      ];

      // Conditionally add validation rule for merchant_id if Auth::user()->merchant_id === 0
      if (Auth::user()->merchant_id === 0) {
        $rules['merchant_id'] = 'required|integer';
      }

      $customMessages = [
        'name.required' => 'The username is required.',
        'name.unique' => 'The username is already taken.',
        'password.required' => 'The password is required.',
        'role_id.required' => 'The role is required.',
        'status.required' => 'The status is required.',
        'merchant_id.required' => 'The merchant is required.',
      ];

      $request->validate($rules, $customMessages);
    } catch (ValidationException $e) {
      return response()->json(['errors' => $e->errors()], 422);
    }

    // return \DB::transaction(function () use ($request) {
      $merchantId = Auth::user()->merchant_id === 0 ? $request->input('merchant_id') : Auth::user()->merchant_id;

      $user = new User([
        'name' => $request->input('name'),
        'password' => bcrypt($request->input('password')),
        'role_id' => $request->input('role_id'),
        'merchant_id' => $merchantId,
        'status' => $request->input('status'),
      ]);

      $user->save();

      return response()->json(['message' => 'User created successfully']);
    // });
  }

  public function editUser(Request $request, User $user)
  {
    $this->permissionAccess('setting-user', 'permission_role_update');

    try {

      $user=User::find($request->input('user_id'));

      $rules = [
        'name' => [
          'required',
          'string',
          Rule::unique('nano_merchant_user', 'name')
          ->ignore($user->id, 'id')
          ->whereNull('deleted_at'),
        ],
        'role_id' => 'required|integer',
        'status' => 'required|integer',
      ];

      if (Auth::user()->merchant_id === 0) {
        $rules['merchant_id'] = 'required|integer';
      }

      $customMessages = [
        'name.required' => 'The username is required.',
        'name.unique' => 'The username is already taken.',
        'role_id.required' => 'The role is required.',
        'merchant_id.required' => 'The merchant is required.',
        'status.required' => 'The status is required.',
      ];

      $request->validate($rules, $customMessages);
    } catch (ValidationException $e) {
      return response()->json(['errors' => $e->errors()], 422);
    }

    return \DB::transaction(function () use ($request, $user) {
      $merchantId = Auth::user()->merchant_id === 0 ? $request->merchant_id : Auth::user()->merchant_id;

      $user->name = $request->name;
      $user->role_id = $request->role_id;
      $user->merchant_id = $merchantId;

      if ($request->filled('password')){
        $user->password = bcrypt($request->input('password'));
      }

      $user->status = $request->status;

      $user->save();

      return response()->json(['message' => 'User updated successfully']);
    });
  }

  public function deleteUser(Request $request)
  {
    $this->permissionAccess('setting-user', 'permission_role_delete');

    return \DB::transaction(function () use ($request) {

      $user=User::find($request->input('user_id'));

      $user->delete();
      return response()->json(['message' => 'User deleted successfully']);
    });
  }
}
