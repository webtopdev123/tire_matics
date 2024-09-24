<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\FleetCategory;
use App\Models\Merchant;

class FleetCategoryController extends Controller {

  public function index() {
    $merchants = Merchant::whereNull('deleted_at')->get();

    $data = [
      'merchants' => $merchants,
    ];

    return view('content.fleet-category.fleet-category', $data);
  }

    public function list(Request $request) {
      $draw = $request->input('draw');
      $start = $request->input('start');
      $length = $request->input('length');
      $order = $request->input('order');

      $search = $request->input('search.value');

      $query = FleetCategory::whereNull('deleted_at')
      ->orderBy('category_name', 'ASC');

      if (!empty($search)) {
        $query->where('category_name', 'like', '%' . $search . '%');
      }

      $list = $query->get();

      $totalRecords = FleetCategory::whereNull('deleted_at')->count();

      $filteredRecords = $query->count();

      return response()->json([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $list,
      ]);
    }

    public function create(Request $request) {
      $merchant = FleetCategory::create([
        'category_name' => $request->input('category_name'),
        'parent_id' => $request->input('parent_id'),
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      return response()->json(['message' => 'FleetCategory created successfully', 'status' => 'success']);
    }

    public function update(Request $request) {
      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      FleetCategory::where('category_id', $request->input('category_id'))
      ->update([
        'category_name' => $request->input('category_name'),
        'parent_id' => $request->input('parent_id'),
        'updated_at' => now(),
      ]);
    }

    public function delete(Request $request) {

      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      FleetCategory::where('category_id', $request->input('id'))
      ->update(['deleted_at' => now()]);

      return response()->json();
    }

}
