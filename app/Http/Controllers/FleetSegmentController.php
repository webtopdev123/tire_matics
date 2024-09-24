<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\FleetSegment;
use App\Models\Merchant;

class FleetSegmentController extends Controller {

  public function index() {
    $merchants = Merchant::whereNull('deleted_at')->get();

    $data = [
      'merchants' => $merchants,
      'segmentParents' => FleetSegment::whereNull('deleted_at')->get(),
    ];

    return view('content.fleet-segment.fleet-segment', $data);
  }

    public function list(Request $request) {
      $draw = $request->input('draw');
      $start = $request->input('start');
      $length = $request->input('length');
      $order = $request->input('order');

      $search = $request->input('search.value');

      $query = FleetSegment::whereNull('deleted_at')
      ->orderBy('segment_name', 'ASC');

      if (!empty($search)) {
        $query->where('segment_name', 'like', '%' . $search . '%');
      }

      $list = $query->get();

      $totalRecords = FleetSegment::whereNull('deleted_at')->count();

      $filteredRecords = $query->count();

      return response()->json([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $list,
      ]);
    }

    public function create(Request $request) {
      $merchant = FleetSegment::create([
        'segment_name' => $request->input('segment_name'),
        'parent_id' => $request->input('parent_id'),
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      return response()->json(['message' => 'FleetSegment created successfully', 'status' => 'success']);
    }

    public function update(Request $request) {
      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      FleetSegment::where('segment_id', $request->input('segment_id'))
      ->update([
        'segment_name' => $request->input('segment_name'),
        'parent_id' => $request->input('parent_id'),
        'updated_at' => now(),
      ]);
    }

    public function delete(Request $request) {

      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      FleetSegment::where('segment_id', $request->input('id'))
      ->update(['deleted_at' => now()]);

      return response()->json();
    }

}
