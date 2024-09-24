<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\PermissionTraits;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\File;
class SettingController extends Controller
{
  use PermissionTraits;

  public function changePassword()
  {
    return view('content.apps.change-password.index');
  }

  public function list(Request $request)
  {
      $this->permissionAccess('change_password', 'permission_role_read');

      $draw = $request->input('draw');
      $start = $request->input('start') ?? 0;
      $length = $request->input('length') ?? 1000;

      $query = User::select(['id','created_at as created_date', 'name'])->where('id', Auth::user()->id);

      // Count the total records without filters
      $totalRecords = User::count();

      // Count the total records with applied filters
      $filteredRecords = $query->count();

      // Apply pagination
      $query->skip($start)->take($length);

      $userList = $query->get();

      $userList = $userList->map(function ($user) {
          $formattedDate = Carbon::parse($user->created_date)->format('Y-m-d H:i:s');
          $user->created_date = $formattedDate;

          return $user;
      });

      // Prepare the response
      $response = [
          'draw' => $draw,
          'recordsTotal' => $totalRecords,
          'recordsFiltered' => $filteredRecords,
          'data' => $userList,
      ];

      return response()->json($response);
  }

  public function updatePassword(Request $request) {
    $request->validate([
      'current_password' => 'required',
      'new_password' => 'required|string|min:6|different:current_password',
      'confirm_password' => 'required|string|same:new_password',
    ]);

    $user = Auth::user();

    $user = User::find($user->id);

    // check if the current password matches the user's password
    // if (!Hash::check($request->input('new_password'), $user->password)) {
    //   return response()->json(['errors' => ['current_password' => ['The current password is incorrect.']]], 422);
    // }
    if (!Hash::check($request->input('current_password'), $user->password)) {
      return response()->json(['errors' => ['current_password' => ['The current password is incorrect.']]], 422);
    }
  

    // $user->password = Hash::make($request->new_password);
    $user->password = bcrypt($request->input('new_password'));
    $user->save();

    return response()->json(['message' => 'Password updated successfully']);
  }

  public function merchantEdit()
  {
    return view('content.apps.merchant-edit.index');
  }

  public function merchantList(Request $request)
  {
    $this->permissionAccess('merchant_edit', 'permission_role_read');

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

    $query = Merchant::select(['merchant_id', 'created_at as created_date', 'merchant_name', 'merchant_registration_number','merchant_url','merchant_whatsapp','merchant_facebook','merchant_instagram','merchant_tiktok','merchant_logo','merchant_priceshow','merchant_skincolor', 'merchant_logo','merchant_email','merchant_phone'])
        ->where('merchant_id', Auth::user()->merchant_id)
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

  public function updateMerchant(Request $request, Merchant $merchant)
  {
    $this->permissionAccess('merchant', 'permission_role_update');

    try {
        $rules = [
            'merchant_logo' => 'nullable|file|image|mimes:jpeg,png,gif,bmp,tiff,webp',
            'merchant_url' => 'string',
            'merchant_whatsapp' => 'string',
            'merchant_facebook' => 'nullable',
            'merchant_instagram' => 'nullable',
            'merchant_tiktok' => 'nullable',
            'merchant_skincolor' => 'string',
            'merchant_email' => 'email',
            'merchant_phone' => 'string',
        ];

        // Validate each field individually
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }

    return DB::transaction(function () use ($merchant, $request) {
        $merchantOld = Merchant::find($request->input('merchant_id'));

        if (!$merchantOld) {
            return response()->json(['error' => 'Merchant not found'], 404);
        }

        // Handle image upload
        if ($request->hasFile('merchant_logo')) {
            $image = $request->file('merchant_logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Move the original image to the uploads directory
            $image->move(public_path('uploads/merchant_logo'), $imageName);

            // Resize the image if needed
            $tempImage = Image::make(public_path('uploads/merchant_logo/' . $imageName));
            if ($tempImage->width() > 400 || $tempImage->height() > 400) {
                $tempImage->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $tempImage->save(public_path('uploads/merchant_logo/' . $imageName));
            }
        }

        $priceShow=0;
        if($request->has('merchant_priceshow') && $request->input('merchant_priceshow') == 1){
          $priceShow=1;
        }

        if($request->hasFile('merchant_logo')) {
          // Update the merchant
          $merchantOld->update([
            'merchant_url' => $request->input('merchant_url'),
            'merchant_whatsapp' => $request->input('merchant_whatsapp'),
            'merchant_facebook' => $request->input('merchant_facebook'),
            'merchant_instagram' => $request->input('merchant_instagram'),
            'merchant_tiktok' => $request->input('merchant_tiktok'),
            'merchant_skincolor' => $request->input('merchant_skincolor'),
            'merchant_priceshow' => $priceShow,
            'merchant_logo' => 'uploads/merchant_logo/' . $imageName,
            'merchant_email' => $request->input('merchant_email'),
            'merchant_phone' => $request->input('merchant_phone'),
          ]);
        } else {
          // Update the merchant
          $merchantOld->update([
            'merchant_url' => $request->input('merchant_url'),
            'merchant_whatsapp' => $request->input('merchant_whatsapp'),
            'merchant_facebook' => $request->input('merchant_facebook'),
            'merchant_instagram' => $request->input('merchant_instagram'),
            'merchant_tiktok' => $request->input('merchant_tiktok'),
            'merchant_skincolor' => $request->input('merchant_skincolor'),
            'merchant_priceshow' => $priceShow,
            'merchant_email' => $request->input('merchant_email'),
            'merchant_phone' => $request->input('merchant_phone'),
          ]);
        }

        return response()->json([
          'message' => 'Merchant updated successfully',
        ]);
    });
  }
}
