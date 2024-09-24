<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\FleetTyreInspection;

class FleetTyreInspectionController extends Controller {

  public function create(Request $request) {
    FleetTyreInspection::create([
      'inspection_mileage' => $request->input('inspection_mileage'),
      'inspection_rtd' => $request->input('inspection_rtd'),
      'inspection_psi' => $request->input('inspection_psi'),
      'tyre_id' => $request->input('tyre_id'),
      'fleet_id' => $request->input('fleet_id'),
      'axle_id' => $request->input('axle_id'),
      'axle_position' => $request->input('axle_position'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    return response()->json();
  }

  public function list(Request $request) {
    $query = FleetTyreInspection::whereNull('deleted_at')
    ->with(['tyre' => function($query) {
      $query->select('tyre_id', 'tyre_serial');
    }])
    ->orderBy('created_at', 'ASC');

    if ($request->filled('tyre_id')) {
      $query->where('tyre_id', $request->input('tyre_id'));
    }

    $data = $query->get();

    return response()->json([
      'data' => $data,
    ]);
  }

  public function update(Request $request) {
    $user = $request->user();

    if (!$user) {
      return response()->json(['message' => 'User not found'], 405);
    }

    FleetTyreInspection::where('inspection_id', $request->input('inspection_id'))
    ->update([
      'inspection_mileage' => $request->input('inspection_mileage'),
      'inspection_rtd' => $request->input('inspection_rtd'),
      'inspection_psi' => $request->input('inspection_psi'),
      'updated_at' => now(),
    ]);
    
  }

  public function delete(Request $request) {
    $user = $request->user();

    if (!$user) {
      return response()->json(['message' => 'User not found'], 405);
    }

    FleetTyreInspection::where('inspection_id', $request->input('id'))
    ->update(['deleted_at' => now()]);

    return response()->json();
  }

}
