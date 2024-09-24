<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TyreBrandModelSize;
use App\Models\Merchant;

class TyreBrandModelSizeController extends Controller {

    public function list(Request $request) {

      $draw = $request->input('draw');
      $start = $request->input('start') ?? 0;
      $length = $request->input('length') ?? 10;
      $search = $request->input('search.value');

      $orderBy = 'size_id';
      $orderDir = 'desc';


      $query = TyreBrandModelSize::whereNull('deleted_at')
      ->where('model_id', $request->input('model_id'));

      if (!empty($search)) {
        $query->where(function ($subQuery) use ($search) {
            $subQuery->where('size_name', 'like', '%' . $search . '%');
        });
      }


      $totalRecords = TyreBrandModelSize::
       where('model_id', $request->input('model_id'))
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

    public function get(Request $request) {
      $size_id = $request->get('size_id');

      if(!$size_id){
        return response()->json([], 400);
      }

      $result = TyreBrandModelSize::where('size_id', $size_id)->whereNull('deleted_at')->first();

      return response()->json([
        'data' => $result,
      ]);
    }

    public function create(Request $request) {

      $merchant = TyreBrandModelSize::create([
        'size_name' => $request->input('size_name'),
	      'size_tread_depth' => $request->input('size_tread_depth'),
	      'size_psi' => $request->input('size_psi'),
	      'model_id' => $request->input('model_id'),
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

      TyreBrandModelSize::where('size_id', $request->input('size_id'))
      ->update([
        'size_name' => $request->input('size_name'),
	      'size_tread_depth' => $request->input('size_tread_depth'),
        'size_psi' => $request->input('size_psi'),
        'updated_at' => now(),
      ]);
    }

    public function delete(Request $request) {
      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      TyreBrandModelSize::where('size_id', $request->input('id'))
      ->update(['deleted_at' => now()]);

      return response()->json();
    }

}
