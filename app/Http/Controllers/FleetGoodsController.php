<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\FleetGoods;
use App\Models\Merchant;

class FleetGoodsController extends Controller {

  public function index() {
    $merchants = Merchant::whereNull('deleted_at')->get();

    $data = [
      'merchants' => $merchants,
    ];

    return view('content.fleet-good.fleet-good', $data);
  }

    public function list(Request $request) {
      $query = FleetGoods::whereNull('deleted_at');
      $list = $query->get();

      return response()->json([
        'data' => $list,
      ]);
    }

    public function create(Request $request) {
      $merchant = FleetGoods::create([
        'goods_name' => $request->input('goods_name'),
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      return response()->json(['message' => 'FleetGoods created successfully', 'status' => 'success']);
    }

    public function update(Request $request) {
      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      FleetGoods::where('goods_id', $request->input('goods_id'))
      ->update([
        'goods_name' => $request->input('goods_name'),
        'updated_at' => now(),
      ]);
    }

    public function delete(Request $request) {
      $user = $request->user();

      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      FleetGoods::where('goods_id', $request->input('id'))
      ->update(['deleted_at' => now()]);

      return response()->json();
    }

}
