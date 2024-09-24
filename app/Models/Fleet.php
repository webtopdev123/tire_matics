<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fleet extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_fleet';

  protected $primaryKey = 'fleet_id';
  protected $guarded = ['fleet_id'];


  public function fleetbrand() {
    return $this->belongsTo(FleetBrand::class, 'fleet_brand_id', 'brand_id');
  }

  public function fleetbrandmodel() {
    return $this->belongsTo(FleetBrandModel::class, 'fleet_brand_model_id', 'model_id');
  }

  public function FleetConfiguration() {
    return $this->belongsTo(FleetConfiguration::class, 'configuration_id', 'configuration_id');
  }

  public function fleetcategory() {
    return $this->belongsTo(FleetCategory::class, 'category_id', 'category_id');
  }

  public function fleetsegment() {
    return $this->belongsTo(FleetSegment::class, 'segment_id', 'segment_id');
  }

  public function goods() {
    return $this->belongsToMany(FleetGoods::class, 'nano_setting_goods_relation', 'fleet_id', 'goods_id');
  }

}
