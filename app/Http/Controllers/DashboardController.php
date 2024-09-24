<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\PermissionTraits;

use App\Models\FleetTyre;
use App\Models\FleetTyreLog;
use App\Models\FleetAxle;
use App\Models\FleetTyreInspection;

class DashboardController extends Controller {
  use PermissionTraits;

  public function index() {
    if (Auth::user()->merchant_id !== 0) {
      return view('content.dashboard.dashboard-charts');
    }

    return view('content.dashboard.index');

  }

  public function getCharts(Request $request) {

    $filterYear = $request->input('filterYear');

    $unitTyres = FleetTyre::select(
      'tyre_type',
      DB::raw('MONTH(created_at) as month'),
      DB::raw('COUNT(tyre_id) as tyre_count'),
    )
    ->where('merchant_id', Auth::user()->merchant_id)
    ->whereNull('deleted_at')
    // ->whereYear('created_at', date('Y'))
    ->whereYear('created_at', $filterYear)
    ->groupBy('tyre_type', 'month')
    ->orderBy('tyre_type')
    ->orderBy('month')
    ->get();

    $costTyres = FleetTyre::select(
      'tyre_type',
      DB::raw('MONTH(created_at) as month'),
      DB::raw('SUM(tyre_price) as total_price')
    )
    ->where('merchant_id', Auth::user()->merchant_id)
    ->whereNull('deleted_at')
    // ->whereYear('created_at', date('Y'))
    ->whereYear('created_at', $filterYear)
    ->groupBy('tyre_type', 'month')
    ->orderBy('tyre_type')
    ->orderBy('month')
    ->get();

    $unitPurchaseTyres = FleetTyre::select(
      'tyre_type',
      DB::raw('MONTH(created_at) as month'),
      DB::raw('COUNT(tyre_id) as tyre_count'),
    )
    ->where('merchant_id', Auth::user()->merchant_id)
    ->whereNull('deleted_at')
    // ->whereYear('created_at', date('Y'))
    ->whereYear('created_at', $filterYear)
    ->where('tyre_status', 'INSTALLED')
    ->groupBy('tyre_type', 'month')
    ->orderBy('tyre_type')
    ->orderBy('month')
    ->get();

    $costPurchaseTyres = FleetTyre::select(
      'tyre_type',
      DB::raw('MONTH(created_at) as month'),
      DB::raw('SUM(tyre_price) as total_price')
    )
    ->where('merchant_id', Auth::user()->merchant_id)
    ->whereNull('deleted_at')
    // ->whereYear('created_at', date('Y'))
    ->whereYear('created_at', $filterYear)
    ->where('tyre_status', 'INSTALLED')
    ->groupBy('tyre_type', 'month')
    ->orderBy('tyre_type')
    ->orderBy('month')
    ->get();

    $tyrePerStatus = FleetTyre::whereNull('deleted_at')
    ->where('merchant_id', Auth::user()->merchant_id)
    // ->whereYear('created_at', date('Y'))
    ->whereYear('created_at', $filterYear)
    ->select('tyre_status', DB::raw('COUNT(tyre_id) as tyre_count'))
    ->groupBy('tyre_status')
    ->get();

    return response()->json([
      'unitTyres'    => $unitTyres,
      'costTyres'     => $costTyres,
      'unitPurchaseTyres' => $unitPurchaseTyres,
      'costPurchaseTyres' => $costPurchaseTyres,
      'tyrePerStatus' => $tyrePerStatus,
    ]);
  }

  public function getTyreCPKCharts(Request $request){
    $filterYear = $request->input('filterYear');
    
    $axle_tyres = ['DRIVE', 'BOGIE', 'STEERING', 'TRAILER'];
    \DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query


    $query = FleetTyre::join('nano_fleet_axle','nano_fleet_tyre.axle_id','=','nano_fleet_axle.axle_id')
      ->whereNull('nano_fleet_tyre.deleted_at')
      ->whereYear('nano_fleet_tyre.created_at', $filterYear)
      ->where('nano_fleet_axle.axle_type','!=', 'SPARE')
      ->where('nano_fleet_tyre.merchant_id', Auth::user()->merchant_id)
      ->select('nano_fleet_axle.axle_type', 'nano_fleet_tyre.tyre_type', 'nano_fleet_axle.fleet_id', 'nano_fleet_tyre.tyre_id', 'nano_fleet_tyre.tyre_price')
      ->orderBy('nano_fleet_axle.axle_type');

    if ($request->filled('fleet_id')) {
      $query->where('nano_fleet_axle.fleet_id', $request->input('fleet_id'));
    }

    $results = $query->get();
    $outputs = array();
    foreach($results as $result){
        $tyre_id = $result['tyre_id'];
        $fleet_id = $result['fleet_id'];
        //get tyre info
        $tyre_info = FleetTyre::where('tyre_id', $tyre_id)->whereNull('deleted_at')->first();

        $firstRecord = FleetTyreInspection::where('tyre_id', $tyre_id)
        ->where('fleet_id', $fleet_id)
        ->whereNull('deleted_at')
        ->orderBy('inspection_id', 'asc')
        ->first();

        if($firstRecord){
          $lastRecord = FleetTyreInspection::where('tyre_id', $tyre_id)
          ->where('fleet_id', $fleet_id)
          ->whereNull('deleted_at')
          ->where('inspection_id', '!=', $firstRecord->inspection_id)
          ->orderBy('inspection_id', 'desc')
          ->first();

          if($lastRecord){
            $depthDiff = abs($firstRecord->inspection_rtd - $lastRecord->inspection_rtd);
            $mileageDiff = abs($firstRecord->inspection_mileage - $lastRecord->inspection_mileage);

            $tyre_cost_km = number_format(round($result->tyre_price / $mileageDiff, 3),3);

            $flag = false;
            if($outputs){
              foreach($outputs as &$output){
                if($output['axle_type'] == $result->axle_type && $output['tyre_type'] == $result->tyre_type){
                  $output['tyre_cost_km'] += $tyre_cost_km;
                  $output['number'] ++;

                  $flag = true;
                }
              }
            }

            if(!$flag){
              $outputs[] = array(
                'axle_type' => $result->axle_type,
                'tyre_type' => $result->tyre_type,
                'tyre_cost_km' => $tyre_cost_km,
                'number' => 1
              );
            }

          }
        }
    }


    return response()->json([
      'data'    => $outputs,
    ]);
  }

}
