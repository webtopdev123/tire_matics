<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\FleetAxle;
use App\Models\Merchant;

class FleetAxleController extends Controller {

    public function list(Request $request) {

      $query = FleetAxle::whereNull('deleted_at')
      ->where('fleet_id', $request->input('fleet_id'))
      ->withCount(['tyres as total_tyre']);
      
      $data = $query->get();
      
      return response()->json([
        'data' => $data,
      ]);

    }
    
    public function create(Request $request) {
      FleetAxle::create([
        'axle_tyre_places' => $request->input('axle_tyre_places'),
        'fleet_id' => $request->input('fleet_id'),
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
  
      FleetAxle::where('axle_id', $request->input('axle_id'))
      ->update([
        'axle_tyre_places' => $request->input('axle_tyre_places'),
        'updated_at' => now(),
      ]);
    }

    public function delete(Request $request) {
      $user = $request->user();
      
      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      FleetAxle::where('axle_id', $request->input('id'))
      ->update(['deleted_at' => now()]);
      
      return response()->json();
    }

}
