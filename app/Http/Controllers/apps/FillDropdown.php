<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FillDropdown extends Controller
{
    public function roles(Request $request)
    {
        $userRoleId = Auth::user()->role_id;

        $roles = Role::where('merchant_id', Auth::user()->merchant_id)
            ->select('role_name as name', 'role_id as value')
            ->get();

        return response()->json($roles);
    }

    public function users(Request $request)
    {
        $users = User::where('merchant_id', Auth::user()->merchant_id)
            ->where('id', '!=', 1)
            ->select('name as name', 'id as value')
            ->get();

        return response()->json($users);
    }


    public function merchant(Request $request)
    {
        $merchant = Merchant::select('merchant_name as name', 'merchant_id as value')->get();

        return response()->json($merchant);
    }

    public function productCategory(Request $request)
    {
        $category = ProductCategory::select('category_name as name', 'category_id as value')
            ->get();

        return response()->json($category);
    }

  
    
}
