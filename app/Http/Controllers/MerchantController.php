<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Models\Merchant;
use App\Models\Permission;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Models\MerchantBranch;
use App\Models\PermissionRole;
use App\Models\MerchantContent;
use Illuminate\Validation\Rule;
use App\Traits\PermissionTraits;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MerchantController extends Controller
{
    use PermissionTraits;

    public function index()
    {
        $this->permissionAccess('merchant', 'permission_role_read');

        if (Auth::user()->merchant_id !== 0) {
            return redirect()->route('dashboard.index');
        }

        return view('content.merchant.index');
    }

    public function list(Request $request)
    {
        $this->permissionAccess('merchant', 'permission_role_read');

        $draw = $request->input('draw');
        $start = $request->input('start') ?? 0;
        $length = $request->input('length') ?? 1000;
        $order = $request->input('order');

        // Default order by column and direction
        $orderBy = 'merchant_id';
        $orderDir = 'desc';

        if (!empty($order) && is_array($order) && count($order) > 0) {
            $columnIndex = $order[0]['column'];
            $columns = $request->input('columns');
            $columnName = $columns[$columnIndex]['data'];
            $orderDir = $order[0]['dir'];

            // You can map the column name to the corresponding database column if needed
            switch ($columnName) {
                case 'merchant_name':
                    $orderBy = 'merchant_name';
                    break;
                case 'created_date':
                    $orderBy = 'created_date';
                    break;
                default:
                    $orderBy = 'merchant_id'; // Default order column
                    break;
            }
        }

        $query = Merchant::select(['merchant_id', 'created_at as created_date', 'merchant_name'])
            ->orderBy($orderBy, $orderDir);

        $search = $request->input('search.value');

        if (!empty($search)) {
            $query->where('merchant_name', 'like', '%' . $search . '%');
        }

        // Count the total records without filters
        $totalRecords = Merchant::count();

        // Count the total records with applied filters
        $filteredRecords = $query->count();

        // Apply pagination
        $query->skip($start)->take($length);

        $merchantList = $query->get();

        $merchantList = $merchantList->map(function ($merchant) {
            $formattedDate = Carbon::parse($merchant->created_date)->format('Y-m-d H:i:s');
            $merchant->created_date = $formattedDate;

            return $merchant;
        });

        // Prepare the response
        $response = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $merchantList,
        ];

        return response()->json($response);
    }


    public function store(Request $request)
    {
        $this->permissionAccess('merchant', 'permission_role_create');

        try {
            // Store a new merchant in the database
            $request->validate(
                [
                    'merchant_name' => 'required|string',
                    'name' => ['required', 'string', Rule::unique('nano_merchant_user', 'name')->whereNull('deleted_at')],
                    'password' => 'required|string',
                ],
                [
                    'merchant_name.required' => 'The merchant name is required.',
                    'name.required' => 'The username is required.',
                    'name.unique' => 'The username is already taken.',
                ],
            );
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return DB::transaction(function () use ($request) {

          $merchant = Merchant::create([
            'merchant_name' => $request->input('merchant_name'),
            'created_at' => now(),
          ]);

          $merchant->save();

          $roleAdmin = Role::create([
            'role_name' => 'Admin',
            'merchant_id' => $merchant->merchant_id,
          ]);

          if($roleAdmin){
            User::create([
              'name' => $request->input('name'),
              'password' => bcrypt($request->input('password')),
              'role_id' => $roleAdmin->role_id,
              'merchant_id' => $merchant->merchant_id,
              'status' => 1,
            ]);
          }

          $newpermission = [
            'fleet',
            "fleet-tyre",
            "change_password",
            "setting-role",
            "setting-user",
          ];

          $permissionArrayAdmin = Permission::whereIn('permission_code', $newpermission)
          ->pluck('permission_id')
          ->all();

          foreach ($permissionArrayAdmin as $permission) {
            PermissionRole::create([
              'permission_role_read' => 1,
              'permission_role_create' => 1,
              'permission_role_update' => 1,
              'permission_role_delete' => 1,
              'role_id' => $roleAdmin->role_id,
              'permission_id' => $permission,
            ]);
          }

          $newpermission = [
            'fleet',
            "fleet-tyre",
            "change_password",
          ];

          $permissionArray = Permission::whereIn('permission_code', $newpermission)
          ->pluck('permission_id')
          ->all();

          $roleWarehouse = Role::create([
            'role_name' => 'Warehouse',
            'merchant_id' => $merchant->merchant_id,
          ]);

          foreach ($permissionArray as $permission) {
            PermissionRole::create([
              'permission_role_read' => 1,
              'permission_role_create' => 1,
              'permission_role_update' => 1,
              'permission_role_delete' => 1,
              'role_id' => $roleWarehouse->role_id,
              'permission_id' => $permission,
            ]);
          }

          return response()->json(['message' => 'Merchant created successfully', 'status' => 'success']);

        });
    }

    public function updateNullMerchantEncryptIds()
    {
        if (Auth::user()->merchant_id !== 0) {
            return redirect()->route('dashboard.index');
        }

        $merchantsToUpdate = Merchant::whereNull('merchant_encrypt_id')->get();

        foreach ($merchantsToUpdate as $merchant) {
            $hashedValue = hash('sha256', "{$merchant->merchant_id}_{$merchant->created_at}_pos");

            $merchant->update(['merchant_encrypt_id' => $hashedValue]);
        }

        return redirect()->route('dashboard.index');
    }


    public function update(Request $request, Merchant $merchant)
    {
        $this->permissionAccess('merchant', 'permission_role_update');

        try {

          $rules = [
            'merchant_name' => 'required|string'
          ];

          // Validate each field individually
          $validator = Validator::make([
              'merchant_name' => $request->input('merchant_name')
          ], $rules);

          if ($validator->fails()) {
              return response()->json(['errors' => $validator->errors()], 422);
          }


        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return DB::transaction(function () use ($merchant, $request) {

          $merchantOld=Merchant::find($request->input('merchant_id'));

          if($merchantOld){

            // $imageName = '';
            // if ($request->hasFile('merchant_logo')) {
            //     $image = $request->file('merchant_logo');
            //     $imageName = time() . '.' . $image->getClientOriginalExtension();

            //     // Move the original image to the uploads directory
            //     $image->move(public_path('uploads'), $imageName);

            //     $tempImage = Image::make(public_path("uploads/{$imageName}"));

            //     if ($tempImage->width() > 100 || $tempImage->height() > 100) {
            //         $tempImage->resize(100, 100, function ($constraint) {
            //             $constraint->aspectRatio();
            //             $constraint->upsize();
            //         });

            //         $tempImage->save(public_path("uploads/{$imageName}"));
            //     }
            // }

            // if ($imageName != '') {
            //     $imageName = 'uploads/' . $imageName;
            // }
            // else{
            //   $imageName=$merchantOld->merchant_logo;
            // }

            // $priceShow=0;

            // if($request->has('merchant_priceshow') && $request->input('merchant_priceshow') == 1){
            //   $priceShow=1;
            // }
            // $request->merge(['merchant_priceshow' => $priceShow]);

            $merchantOld->update(array_merge($request->all(),));

            // $merchantOld->merchant_logo = $imageName;

            $merchantOld->save();

          }

          return response()->json(['message' => 'Merchant updated successfully']);
        });
    }

    public function destroy(Merchant $merchant, Request $request)
    {
        $this->permissionAccess('merchant', 'permission_role_delete');

        $merchant
            ->users()
            ->where('role_id', '!=', 1)
            ->delete();
        $merchant
            ->roles()
            ->where('role_id', '!=', 1)
            ->delete();
        $merchant->delete();

    return response()->json(['message' => 'Merchant deleted successfully']);
  }

  public function switch (Request $request,$merchant_id)
  {

    $userId=Auth::user()->id;

    $sessionUserId=0;
    $user_userId = Session::get('user_userId');
    if ($user_userId !== null) {
      $sessionUserId=$user_userId;
    }

    $sessionRoleId=0;
    $user_userRoleId = Session::get('user_userRoleId');
    if ($user_userRoleId !== null) {
      $sessionRoleId=$user_userRoleId;
    }

    // for super admin
    if($userId == 1 || $sessionUserId == 1){

      $this->permissionAccess('merchant', 'permission_role_read');

      $merchant = Merchant::find($merchant_id);

      if (!$merchant && $merchant_id != 0) {
        abort(404, 'Merchant not found');
      }

      // Update the user's merchant_id if $merchant_id is set
      if (isset($merchant_id)) {

        $newMerchantAdmin = User::where('merchant_id', $merchant_id)
                  ->orderBy('id', 'asc')
                  ->first();

        Auth::user()->merchant_id = $merchant_id;

        if($sessionUserId != 0){
          Auth::user()->id=$sessionUserId;
        }

        if($sessionRoleId != 0){
          Auth::user()->role_id=$sessionRoleId;
        }

        Auth::user()->save();


        if($merchant_id != 0){

          $currentUserId=Auth::user()->id;
          $userUserId= Session::get('user_userId');
          if (isset($userUserId) && !empty($userUserId)) {
            $currentUserId=$userUserId;
          }

          $currentRoleId=Auth::user()->role_id;
          $userUserRoleId= Session::get('user_userRoleId');
          if (isset($userUserRoleId) && !empty($userUserRoleId)) {
            $currentRoleId=$userUserRoleId;
          }

          if (!isset($userUserId) && empty($userUserId)) {
            Session::put('user_userId', $currentUserId);
          }

          if (!isset($userUserRoleId) && empty($userUserRoleId)) {
            Session::put('user_userRoleId', $currentRoleId);
          }

          Session::put('current_switched_userId', $newMerchantAdmin->id);
          Session::put('current_switched_userRoleName', $newMerchantAdmin->name);
          Session::put('current_switched_userRoleId', $newMerchantAdmin->role_id);
        }
        else
        {
          Session::forget('user_userId');
          Session::forget('user_userRoleId');
          Session::forget('current_switched_userId');
          Session::forget('current_switched_userRoleName');
          Session::forget('current_switched_userRoleId');
        }
        
      }

      if($merchant_id == '0')
        return redirect()->route('merchant.index');

      // You can also perform any other actions related to switching the merchant here
      // return redirect()->back();
      return redirect()->route('dashboard');

    }
  }

    public function switch_render()
    {
        $this->permissionAccess('merchant', 'permission_role_read');

        $arrayMerchant = Merchant::orderBy('merchant_name')->get();

        $html = View::make('content.merchant.switch_render', ['arrayMerchant' => $arrayMerchant])->render();

        return response()->json($html);
    }
}
