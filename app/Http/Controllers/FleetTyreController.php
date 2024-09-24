<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\FleetTyre;
use App\Models\Merchant;
use App\Models\TyreBrand;
use App\Models\TyreBrandModel;
use App\Models\TyreBrandModelSize;
use App\Models\FleetAxle;
use App\Models\FleetTyreLog;
use App\Models\FleetTyreInspection;


class FleetTyreController extends Controller {
  public function index(Request $request) {
    $merchants = Merchant::whereNull('deleted_at')->get();

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

    $data = [
      'merchants' => $merchants,
      'tyreBrands' => TyreBrand::whereNull('deleted_at')->get(),
      'tyreBrandModels' => TyreBrandModel::whereNull('deleted_at')->get(),
      'tyreBrandModelSizes' => TyreBrandModelSize::whereNull('deleted_at')->get(),
      'tyreStatus'    => $tyreStatus,
      'tyreType'      => $tyreType,
    ];

    return view('content.fleet-tyre.fleet-tyre', $data);
  }

    public function list(Request $request) {
      $query = FleetTyre::whereNull('deleted_at')
      ->where('merchant_id', Auth::user()->merchant_id)
      ->with([
        'tyreBrand',
        'tyreBrandModel',
        'tyreBrandModelSize'
      ]);

      $filter_installed = $request->get('filter_installed');
      if($filter_installed == 1){
        $query->where('axle_id', 0);
      }

      //filter status
      if ($request->get('filter_status') != '') {
        $query->where('tyre_status', $request->get('filter_status'));
      }

      //filter type
      if ($request->get('filter_type') != '') {
        $query->where('tyre_type', $request->get('filter_type'));
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

      $query->where('merchant_id', Auth::user()->merchant_id);

      $data = $query->get();
      foreach($data as &$tyre){
        if(array_key_exists($tyre->tyre_status, $tyreStatus)){
          $tyre->status_name = $tyreStatus[strtoupper($tyre->tyre_status)];
        } else {
          $tyre->status_name = $tyre->tyre_status;
        }

        if(array_key_exists($tyre->tyre_type, $tyreType)){
          $tyre->type_name = $tyreType[strtoupper($tyre->tyre_type)];
        } else {
          $tyre->type_name = $tyre->tyre_type;
        }
      }

      return response()->json([
        'data' => $data,
      ]);
    }

    public function get(Request $request) {
      $tyre_id = $request->get('tyre_id');

      if(!$tyre_id){
        return response()->json([], 400);
      }

      $query = FleetTyre::where('tyre_id', $tyre_id)->whereNull('deleted_at')->with([
        'tyreBrand',
        'tyreBrandModel',
        'tyreBrandModelSize'
      ]);

      $tyre = $query->first();

      if (!$tyre) {
        return response()->json([], 404);
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

      if(array_key_exists($tyre->tyre_status, $tyreStatus)){
        $tyre->status_name = $tyreStatus[strtoupper($tyre->tyre_status)];
      } else {
        $tyre->status_name = $tyre->tyre_status;
      }

      if(array_key_exists($tyre->tyre_type, $tyreType)){
        $tyre->type_name = $tyreType[strtoupper($tyre->tyre_type)];
      } else {
        $tyre->type_name = $tyre->tyre_type;
      }

      $tyre->updated_at = Carbon::createFromFormat('Y-m-d H:i:s',$tyre->updated_at)->format('d/m/Y');

      $tyre->position_name = $tyre->tyre_type;

      $tyre_history = FleetTyreLog::where('log_status', 'INSTALLED')
      ->where('tyre_id', $tyre_id)
      ->orderBy('log_id', 'DESC')
      ->first();

      $tyre->installed_date = '';

      if($tyre_history){
        $tyre->installed_date = Carbon::createFromFormat('Y-m-d H:i:s',$tyre_history->created_at)->format('d/m/Y');
      }

      //Projection KM calculation

      $tyre->tyre_projection = "-";
      $tyre->tyre_cost_km = "-";

      // GET axle_info to find fleet_id
      $axle_info = FleetAxle::where('axle_id', $tyre->axle_id)->first();

      $logCount = FleetTyreInspection::where('tyre_id', $tyre_id)
      ->where('fleet_id', $axle_info->fleet_id)
      ->whereNull('deleted_at')
      ->count();

      if ($logCount > 1) {
        $firstRecord = FleetTyreInspection::where('tyre_id', $tyre_id)
        ->where('fleet_id', $axle_info->fleet_id)
        ->whereNull('deleted_at')
        ->orderBy('inspection_id', 'asc')
        ->first();

        $lastRecord = FleetTyreInspection::where('tyre_id', $tyre_id)
        ->where('fleet_id', $axle_info->fleet_id)
        ->whereNull('deleted_at')
        ->orderBy('inspection_id', 'desc')
        ->first();
        
        $depthDiff = abs($firstRecord->inspection_rtd - $lastRecord->inspection_rtd);

        if ($depthDiff == 0) {
          $depthDiff = 1;
        }

        $mileageDiff = abs($firstRecord->inspection_mileage - $lastRecord->inspection_mileage);

        if ($mileageDiff == 0) {
          $mileageDiff = 1;
        }

        $tyre->tyre_projection = number_format(round($mileageDiff / $depthDiff * ($firstRecord->inspection_rtd - 3), 2))."KM";

        $tyre->tyre_cost_km = "RM". number_format(round($tyre->tyre_price / $mileageDiff, 3),3)."/KM";

      }

      return response()->json([
        'data' => $tyre,
      ]);
    }

  public function create(Request $request) {

    $tyreSerials = $request->input('tyre_serial');

    foreach ($tyreSerials as $serial) {
      $merchant = FleetTyre::create([
        'tyre_serial'     => $serial,
        'tyre_attribute'  => '',
        'tyre_odt'        => $request->input('tyre_odt'),
        'tyre_psi'        => $request->input('tyre_psi'),
        'tyre_price'      => $request->input('tyre_price'),
        'tyre_year'       => $request->input('tyre_year'),
        // 'tyre_status'     => $request->input('tyre_status'),
        'tyre_status'     => 'AVAILABLE',
        'tyre_type'   => $request->input('tyre_type'),
        'tyre_brand_id'   => $request->input('tyre_brand_id'),
        'tyre_model_id'   => $request->input('tyre_model_id'),
        'tyre_size_id'    => $request->input('tyre_size_id'),
        'axle_id'         => 0,
        'merchant_id' => Auth::user()->merchant_id,
        'created_at'      => now(),
        'updated_at'      => now(),
      ]);
    }

    return response()->json();
  }

  public function update(Request $request) {
    $user = $request->user();

    if (!$user) {
      return response()->json(['message' => 'User not found'], 405);
    }

    FleetTyre::where('tyre_id', $request->input('tyre_id'))
    ->update([
      'tyre_serial'     => $request->input('tyre_serial'),
      'tyre_odt'        => $request->input('tyre_odt'),
      'tyre_psi'        => $request->input('tyre_psi'),
      'tyre_price'      => $request->input('tyre_price'),
      'tyre_year'       => $request->input('tyre_year'),
      // 'tyre_status'     => $request->input('tyre_status'),
      'tyre_type'   => $request->input('tyre_type'),
      'tyre_brand_id'   => $request->input('tyre_brand_id'),
      'tyre_model_id'   => $request->input('tyre_model_id'),
      'tyre_size_id'    => $request->input('tyre_size_id'),
      'updated_at'      => now(),
    ]);

    return response()->json();
  }

  public function delete(Request $request) {
    $user = $request->user();

    if (!$user) {
      return response()->json(['message' => 'User not found'], 405);
    }

    FleetTyre::where('tyre_id', $request->input('id'))
    ->update(['deleted_at' => now()]);

    return response()->json();
  }

  public function listDropdown(Request $request) {
    $query = FleetTyre::whereNull('deleted_at')
    ->where('axle_id', 0)
    ->select('tyre_id', 'tyre_serial');

    $data = $query->get();

    return response()->json([
      'data' => $data,
    ]);
  }

  public function setTyre(Request $request) {
    $user = $request->user();

    if (!$user) {
      return response()->json(['message' => 'User not found'], 405);
    }

    FleetTyre::where('tyre_id', $request->input('tyre_id'))
    ->update([
      'axle_id' => $request->input('axle_id'),
      'tyre_type' => $request->input('tyre_type'),
      'updated_at' => now(),
    ]);

    return response()->json();
  }

  public function unsetTyre(Request $request) {
    $user = $request->user();

    if (!$user) {
      return response()->json(['message' => 'User not found'], 405);
    }

    FleetTyre::where('tyre_id', $request->input('id'))
    ->update([
      'axle_id' => 0,
      'updated_at' => now(),
    ]);

    return response()->json();
  }

  public function updatePosition(Request $request) {
    $user = $request->user();

    if (!$user) {
      return response()->json(['message' => 'User not found'], 405);
    }

    FleetTyre::where('tyre_id', $request->input('tyre_id'))
    ->update([
      'tyre_type' => $request->input('tyre_type'),
      'updated_at' => now(),
    ]);

    return response()->json();
  }

}
