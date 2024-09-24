<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TyreBrand;
use App\Models\Merchant;

class TyreBrandController extends Controller {
  
  public function index() {
    $merchants = Merchant::whereNull('deleted_at')->get();

    $data = [
      'merchants' => $merchants,
    ];

    return view('content.tyre-brand.tyre-brand', $data);

        // $this->permissionAccess('merchant', 'permission_role_read');

        // if (Auth::user()->merchant_id !== 0) {
        //     return redirect()->route('dashboard.index');
        // }
  }

    public function list(Request $request) {

      $draw = $request->input('draw');
      $start = $request->input('start') ?? 0;
      $length = $request->input('length') ?? 10;
      $search = $request->input('search.value');

      $orderBy = 'brand_id';
      $orderDir = 'desc';


      $query = TyreBrand::whereNull('deleted_at')
      ->withCount(['tyreBrandModels as total_models']);

      if (!empty($search)) {
        $query->where(function ($subQuery) use ($search) {
            $subQuery->where('brand_name', 'like', '%' . $search . '%');
        });
      }
    

      $totalRecords = TyreBrand::whereNull('deleted_at')
      ->count();

      $filteredRecords = $query->count();

      $data = $query
          ->orderBy($orderBy, $orderDir)
          ->skip($start)
          ->take($length)
          ->get();

      
      return response()->json([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data,
      ]);
    }
    
    public function create(Request $request) {
      $merchant = TyreBrand::create([
        'brand_name' => $request->input('brand_name'),
        'created_at' => now(),
        'updated_at' => now(),
      ]);
      
      return response()->json(['message' => 'TyreBrand created successfully', 'status' => 'success']);
    }
    
    public function update(Request $request) {
      $user = $request->user();
        
      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }
  
      TyreBrand::where('brand_id', $request->input('brand_id'))
      ->update([
        'brand_name' => $request->input('brand_name'),
        'updated_at' => now(),
      ]);
    }

    public function delete(Request $request) {
      $user = $request->user();
      
      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      TyreBrand::where('brand_id', $request->input('id'))
      ->update(['deleted_at' => now()]);
      
      return response()->json();
    }

}
