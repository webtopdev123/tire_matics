<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TyreBrandModel;
use App\Models\Merchant;

class TyreBrandModelController extends Controller {

    public function list(Request $request) {
      
      $draw = $request->input('draw');
      $start = $request->input('start') ?? 0;
      $length = $request->input('length') ?? 10;
      $search = $request->input('search.value');

      $orderBy = 'model_id';
      $orderDir = 'desc';


      $query = TyreBrandModel::whereNull('deleted_at')
      ->where('brand_id', $request->input('brand_id'));

      if (!empty($search)) {
        $query->where(function ($subQuery) use ($search) {
            $subQuery->where('model_name', 'like', '%' . $search . '%');
        });
      }
    

      $totalRecords = TyreBrandModel::
       where('brand_id', $request->input('brand_id'))
      ->whereNull('deleted_at')
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
      $merchant = TyreBrandModel::create([
        'model_name' => $request->input('model_name'),
        'brand_id' => $request->input('brand_id'),
        'created_at' => now(),
        'updated_at' => now(),
      ]);
      
      return response()->json();
    }
    
    public function update(Request $request) {
      $user = $request->user();
        
      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }
  
      TyreBrandModel::where('model_id', $request->input('model_id'))
      ->update([
        'model_name' => $request->input('model_name'),
        'updated_at' => now(),
      ]);
    }

    public function delete(Request $request) {
      $user = $request->user();
      
      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      TyreBrandModel::where('model_id', $request->input('id'))
      ->update(['deleted_at' => now()]);
      
      return response()->json();
    }

}
