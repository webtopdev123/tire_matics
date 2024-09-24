<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Fleet;
use App\Models\FleetBrand;
use App\Models\FleetBrandModel;
use App\Models\FleetConfiguration;
use App\Models\FleetCategory;
use App\Models\FleetSegment;
use App\Models\FleetGoods;
use App\Models\FleetAxle;

use App\Models\Merchant;

class FleetController extends Controller {

  public function index() {
    $merchants = Merchant::whereNull('deleted_at')->get();

    $data = [
      'merchants' => $merchants,
      'fleetBrands' => FleetBrand::whereNull('deleted_at')->get(),
      'fleetBrandModels' => FleetBrandModel::whereNull('deleted_at')->get(),
      'fleetConfigurations' => FleetConfiguration::whereNull('deleted_at')->get(),
      'fleetBrandCategories' => FleetCategory::whereNull('deleted_at')->get(),
      'fleetGoods' => FleetGoods::whereNull('deleted_at')->get(),
      'fleetSegments' => FleetSegment::whereNull('deleted_at')->get(),
    ];

    return view('content.fleet.fleet', $data);
  }

  public function list(Request $request) {

      $query = Fleet::whereNull('deleted_at')
      ->where('merchant_id', Auth::user()->merchant_id)
      ->with([
        'fleetbrand',
        'fleetbrandmodel',
        'FleetConfiguration',
        'fleetcategory',
        'fleetsegment',
        'goods',
      ]);

      if ($request->get('filter_status') != '') {
        if ($request->get('filter_status') == 1) {
          $query->where('fleet_status', 1);
        }else{
          $query->where('fleet_status', 0);
        }
      }

      $query->where('merchant_id', Auth::user()->merchant_id);

      $list = $query->get();

      return response()->json([
        'data' => $list,
      ]);
    }

    public function create(Request $request) {

      $fleet = Fleet::create([
        'fleet_plate' => $request->input('fleet_plate'),
        'fleet_code' => $request->input('fleet_code'),
        'fleet_rim_width' => $request->input('fleet_rim_width'),
        'fleet_spare_tyre' => $request->input('fleet_spare_tyre'),
        'fleet_bdm' => $request->input('fleet_bdm'),
        'fleet_btm' => $request->input('fleet_btm'),
        'fleet_status' => 1,
        'fleet_brand_id' => $request->input('fleet_brand_id'),
        'fleet_brand_model_id' => $request->input('fleet_brand_model_id'),
        'segment_id' => $request->input('segment_id'),
        'category_id' => $request->input('category_id'),
        'configuration_id' => $request->input('configuration_id'),
        'merchant_id' => Auth::user()->merchant_id,
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      $fleet->goods()->sync($request->input('fleet_goods_id'));

      // get setting from configuration to generate fleetAxle
      $configuration = FleetConfiguration::where('configuration_id', $request->input('configuration_id'))->first();
      $this->generateFleetAxle($fleet->fleet_id, $configuration->configuration_setting);

      return response()->json(['message' => 'Fleet created successfully', 'status' => 'success']);
    }

    public function generateFleetAxle($fleet_id, $setting){

      // remove old values
      FleetAxle::where('fleet_id', $fleet_id)
        ->update(['deleted_at' => now()]);
      // generate new values

      $alxe_rows = explode('-', $setting);
      $row_index = 0;
      foreach($alxe_rows as $alxe_row){
        $row_index ++; // Axle row
        $values = explode(':', $alxe_row);

        if(count($values) == 2){
          // first value is alxe type: STEER - BOGIE - TRAILER
          // second value is number of Tyre only 2 or 4
          if( $values[0] == 2 ){

            FleetAxle::create([
              'axle_tyre_number' => 2,
              'axle_type' => $values[1],
              'axle_position_l1' => 0,
              'axle_position_l2' => -1,
              'axle_position_r1' => 0,
              'axle_position_r2' => -1,
              'axle_row' => $row_index,
              'fleet_id' => $fleet_id,
              'created_at' => now(),
              'updated_at' => now(),
            ]);
          } else if($values[0] == 4){
            FleetAxle::create([
              'axle_tyre_number' => 4,
              'axle_type' => $values[1],
              'axle_position_l1' => 0,
              'axle_position_l2' => 0,
              'axle_position_r1' => 0,
              'axle_position_r2' => 0,
              'axle_row' => $row_index,
              'fleet_id' => $fleet_id,
              'created_at' => now(),
              'updated_at' => now(),
            ]);
          }
        }
      }

      // Create Spare record with axle_row = 0
      FleetAxle::create([
        'axle_tyre_number' => 2,
        'axle_type' => 'SPARE',
        'axle_position_l1' => 0,
        'axle_position_l2' => -1,
        'axle_position_r1' => 0,
        'axle_position_r2' => -1,
        'axle_row' => 0,
        'fleet_id' => $fleet_id,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    public function update(Request $request) {
      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      Fleet::where('fleet_id', $request->input('fleet_id'))
      ->update([
        'fleet_plate' => $request->input('fleet_plate'),
        'fleet_code' => $request->input('fleet_code'),
        'fleet_rim_width' => $request->input('fleet_rim_width'),
        'fleet_spare_tyre' => $request->input('fleet_spare_tyre'),
        'fleet_bdm' => $request->input('fleet_bdm'),
        'fleet_btm' => $request->input('fleet_btm'),
        'fleet_status' => 1,
        'fleet_brand_id' => $request->input('fleet_brand_id'),
        'fleet_brand_model_id' => $request->input('fleet_brand_model_id'),
        'segment_id' => $request->input('segment_id'),
        'category_id' => $request->input('category_id'),
        'updated_at' => now(),
      ]);

      $fleet = Fleet::where('fleet_id', $request->input('fleet_id'))->first();

      $fleet->goods()->sync($request->input('fleet_goods_id'));
    }

    public function delete(Request $request) {
      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      Fleet::where('fleet_id', $request->input('id'))
      ->update(['deleted_at' => now()]);

      return response()->json();
    }

}
