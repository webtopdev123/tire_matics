<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fleet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\FleetGoods;
use App\Models\Merchant;
use App\Models\FleetAxle;
use App\Models\FleetTyre;
use App\Models\FleetTyreLog;
use App\Models\FleetTyreInspection;

class FleetDetailController extends Controller {

  public function index(int $id) {
    if($id){
      $fleet = Fleet::where('fleet_id', $id)->whereNull('deleted_at')->with([
        'fleetbrand',
        'fleetbrandmodel',
        'FleetConfiguration',
        'fleetcategory',
        'fleetsegment',
        'goods',
      ])->first();



      if(!$fleet){
        return redirect()->route('fleet');
      }

      $fleet_axles = FleetAxle::where('fleet_id', $id)->where('axle_type', '!=', 'SPARE')->whereNull('deleted_at')->orderBy('axle_row', 'ASC')->get();
      foreach($fleet_axles as &$fleet_axle){
        if($fleet_axle->axle_position_l2 > 0){
          $fleet_axle->axle_position_l2_info = FleetTyreInspection::where('tyre_id', $fleet_axle->axle_position_l2)->whereNull('deleted_at')->orderBy('inspection_id', 'DESC')->first();
        } else {
          $fleet_axle->axle_position_l2_info = null;
        }

        if($fleet_axle->axle_position_l1 > 0){
          $fleet_axle->axle_position_l1_info = FleetTyreInspection::where('tyre_id', $fleet_axle->axle_position_l1)->whereNull('deleted_at')->orderBy('inspection_id', 'DESC')->first();
        } else {
          $fleet_axle->axle_position_l1_info = null;
        }

        if($fleet_axle->axle_position_r2 > 0){
          $fleet_axle->axle_position_r2_info = FleetTyreInspection::where('tyre_id', $fleet_axle->axle_position_r2)->whereNull('deleted_at')->orderBy('inspection_id', 'DESC')->first();
        } else {
          $fleet_axle->axle_position_r2_info = null;
        }

        if($fleet_axle->axle_position_r1 > 0){
          $fleet_axle->axle_position_r1_info = FleetTyreInspection::where('tyre_id', $fleet_axle->axle_position_r1)->whereNull('deleted_at')->orderBy('inspection_id', 'DESC')->first();
        } else {
          $fleet_axle->axle_position_r1_info = null;
        }
      }
      $fleet_spare = FleetAxle::where('fleet_id', $id)->where('axle_type', 'SPARE')->whereNull('deleted_at')->first();
      if($fleet_spare->axle_position_l1 > 0){
        $fleet_spare->axle_position_l1_info = FleetTyreInspection::where('tyre_id', $fleet_spare->axle_position_l1)->whereNull('deleted_at')->orderBy('inspection_id', 'DESC')->first();
      } else {
        $fleet_spare->axle_position_l1_info = null;
      }
      if($fleet_spare->axle_position_r1 > 0){
        $fleet_spare->axle_position_r1_info = FleetTyreInspection::where('tyre_id', $fleet_spare->axle_position_r1)->whereNull('deleted_at')->orderBy('inspection_id', 'DESC')->first();
      } else {
        $fleet_spare->axle_position_r1_info = null;
      }
    }

    $tyreStatus = array(
      'AVAILABLE' => 'Available',
      'INSTALLED' => 'Installed',
      'RETREADING' => 'Retreading',
      'DISPOSED' => 'Disposed',
      'SPARE' => 'Spare',
    );

    $tyreType = array(
      'NEW' => 'New Tyre',
      'COC' => 'Customer Own Casing',
      'RETREAD' => 'Stock Retread',
      'USED' => 'Used Tyre',
    );

    $merchants = Merchant::whereNull('deleted_at')->get();

    $data = [
      'fleet_id'    => $id,
      'merchants'   => $merchants,
      'fleet'       => $fleet,
      'fleet_axles' => $fleet_axles,
      'fleet_spare' => $fleet_spare,
      'tyreStatus'  => $tyreStatus,
      'tyreType'    => $tyreType,
      'axle_row'    => count($fleet_axles)
    ];

    return view('content.fleet-detail.fleet-detail', $data);
  }

    public function list(Request $request) {
      $query = FleetGoods::whereNull('deleted_at');
      $list = $query->get();

      return response()->json([
        'data' => $list,
      ]);
    }

    public function install(Request $request) {
      $tyre_id = $request->input('tyre_id');
      $fleet_axle_id = $request->input('current_fleet_axle_id');
      $fleet_axle_position = $request->input('current_fleet_axle_position');
      $inspection_mileage = $request->input('inspection_mileage');

      // check if tyre is empty, haven't installed to any fleet_axle
      $tyre_info = FleetTyre::where('tyre_id', $tyre_id)->whereNull('deleted_at')->first();
      if($tyre_info->axle_id == 0){
        //check if tyre was installed, have to reverse it
        $fleet_axle_info = FleetAxle::where('axle_id', $fleet_axle_id)->whereNull('deleted_at')->first();
        if($fleet_axle_info->{$fleet_axle_position} != 0){

          FleetTyre::where('tyre_id', $fleet_axle_info->{$fleet_axle_position})
          ->whereNull('deleted_at')
          ->update([
            'axle_id' => 0,
            'tyre_status'=> 'AVAILABLE',
            'updated_at' => now(),
          ]);

          FleetTyreLog::create([
            'log_status' => 'AVAILABLE',
            'log_type' => 'USED',
            'tyre_id' => $fleet_axle_info->{$fleet_axle_position},
            'fleet_id' => $fleet_axle_info->fleet_id,
            'fleet_axle_id' => $fleet_axle_info->axle_id,
            'fleet_axle_position' => str_replace('axle_position_','', $fleet_axle_position),
            'created_at' => now(),
            'updated_at' => now(),
          ]);

        }

        //update tyre_id to fleet_axle
        FleetAxle::where('axle_id', $fleet_axle_id)
        ->whereNull('deleted_at')
        ->update([
          $fleet_axle_position => $tyre_id,
          'updated_at' => now(),
        ]);

        // update axle_id at fleet_tyre table => 1 tyre only assign once
        FleetTyre::where('tyre_id', $tyre_id)
        ->whereNull('deleted_at')
        ->update([
          'axle_id' => $fleet_axle_id,
          'tyre_status'=> 'INSTALLED',
          'updated_at' => now(),
        ]);

        $success = true;

        FleetTyreLog::create([
          'log_status' => 'INSTALLED',
          'log_type' => $tyre_info->tyre_type,
          'tyre_id' => $tyre_id,
          'fleet_id' => $fleet_axle_info->fleet_id,
          'fleet_axle_id' => $fleet_axle_info->axle_id,
          'fleet_axle_position' => str_replace('axle_position_','', $fleet_axle_position),
          'created_at' => now(),
          'updated_at' => now(),
        ]);

        FleetTyreInspection::create([
          'inspection_mileage' => $inspection_mileage,
          'inspection_rtd' => $tyre_info->tyre_odt,
          'inspection_psi' => $tyre_info->tyre_psi,
          'tyre_id' => $tyre_info->tyre_id,
          'fleet_id' => $fleet_axle_info->fleet_id,
          'axle_id' => $fleet_axle_info->axle_id,
          'axle_position' => $fleet_axle_position,
          'created_at' => now(),
          'updated_at' => now(),
        ]);

      } else {
        $success = false;
      }

      return response()->json([
        'success' => $success,
      ]);
    }

    public function getcharts(Request  $request){
      $outputs = array();
      $tyre_id = $request->post('tyre_id');
      $fleet_id = $request->post('fleet_id');

      if($tyre_id > 0)  {
        //get tyre info
        $tyre_info = FleetTyre::where('tyre_id', $tyre_id)->whereNull('deleted_at')->first();
        \DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query

        $firstRecord = FleetTyreInspection::where('tyre_id', $tyre_id)
        ->where('fleet_id', $fleet_id)
        ->whereNull('deleted_at')
        ->orderBy('inspection_id', 'asc')
        ->first();

        if($firstRecord){
          $records = FleetTyreInspection::where('tyre_id', $tyre_id)
          ->where('fleet_id', $fleet_id)
          ->whereNull('deleted_at')
          ->whereYear('created_at', date('Y'))
          ->orderBy('inspection_id', 'desc')
          ->groupBy('month')

          ->get([DB::raw('MAX(inspection_id) as inspection_id'),
            DB::raw('MONTH(created_at) as month')]);

          if($records){

            $fist_inspection_rtd = $firstRecord->inspection_rtd;
            $fist_inspection_mileage = $firstRecord->inspection_mileage;
            foreach($records as $record){
              $lastRecord = FleetTyreInspection::where('inspection_id', $record->inspection_id)->first();

              $depthDiff = abs($lastRecord->inspection_rtd - $fist_inspection_rtd);
              $mileageDiff = abs($lastRecord->inspection_mileage - $fist_inspection_mileage);

              if($depthDiff == 0 || $mileageDiff == 0){
                $tyre_projection = 0;
                $tyre_cost_km = 0;
              } else {
                $tyre_projection = round($mileageDiff / $depthDiff * ($fist_inspection_rtd - 3), 3);

                $tyre_cost_km = round($tyre_info->tyre_price / $mileageDiff, 3);
              }


              $outputs[] = array(
                'month' => $record->month,
                'tyre_projection' => $tyre_projection,
                'tyre_cost_km' => $tyre_cost_km,
              );
            }
          }
        }
    }


      return response()->json([
        'outputs'    => $outputs,
      ]);
    }


  public function unmount(Request $request) {
    $tyre_id = $request->post('tyre_id');

    $tyre = FleetTyre::where('tyre_id', $tyre_id)->first();
    $tyre_type = $tyre->tyre_type;
    if (!$tyre) {
      return response()->json("Tyre not found", 404);
    }

    $axle = FleetAxle::where('axle_id', $tyre->axle_id)->first();

    if (!$axle) {
      return response()->json("Axle not found. id: ". $tyre->axle_id, 404);
    }


    /*
    Rule:
    if it is NEW .. once installed, it turn to USED
    if it is COC, once installed, it turn to USED RETREAD
    if it is RETREAD, once installed, it turn to USED RETREAD
    if it is USED, once installed, it turn to USED
    */
    $new_tyre_type = 'USED RETREAD';
    if($tyre_type == "NEW" || $tyre_type == "USED"){
      $new_tyre_type = 'USED';
    }

    $tyre->update([
      'axle_id' => 0,
      'tyre_type' => $new_tyre_type,
      'tyre_status'=> $request->input('log_status'),
      'updated_at' => now(),
    ]);
    $fleet_axle_position = '';
    if ($axle->axle_position_l1 == $tyre_id) {
      $axle->update([
        'axle_position_l1' => 0,
        'updated_at' => now(),
      ]);
      $fleet_axle_position = 'l1';
    }else if ($axle->axle_position_l2 == $tyre_id) {
      $axle->update([
        'axle_position_l2' => 0,
        'updated_at' => now(),
      ]);
      $fleet_axle_position = 'l2';
    }else if ($axle->axle_position_r1 == $tyre_id) {
      $axle->update([
        'axle_position_r1' => 0,
        'updated_at' => now(),
      ]);
      $fleet_axle_position = 'r1';
    }else if ($axle->axle_position_r2 == $tyre_id) {
      $axle->update([
        'axle_position_r2' => 0,
        'updated_at' => now(),
      ]);
      $fleet_axle_position = 'r2';
    }

    FleetTyreLog::create([
      'log_status'=> $request->input('log_status'),
      'log_type' => $tyre_type,
      'log_remark'=> $request->input('log_remark') ?? "",
      'tyre_id' => $tyre_id,
      'fleet_id' => $axle->fleet_id,
      'fleet_axle_id' => $axle->axle_id,
      'fleet_axle_position' => $fleet_axle_position,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    return response()->json();
  }
}
