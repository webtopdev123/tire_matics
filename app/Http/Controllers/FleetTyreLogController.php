<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\FleetTyreLog;

class FleetTyreLogController extends Controller {

  public function list(Request $request) {
    $query = FleetTyreLog::whereNull('deleted_at')
    ->with([
      'fleet',
      'axle'
    ])
    ->orderBy('created_at', 'DESC');

    if ($request->filled('tyre_id')) {
      $query->where('tyre_id', $request->input('tyre_id'));
    }

    $results = $query->get();
    // foreach($results as $result){
    //   $result->created_at =  Carbon::createFromFormat('Y-m-d H:i:s',$result->created_at)->format('d/m/Y');
    // }



    return response()->json([
      'data' => $results,
    ]);
  }

}
