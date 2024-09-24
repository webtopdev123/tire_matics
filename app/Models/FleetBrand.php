<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetBrand extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_setting_brand';

  protected $primaryKey = 'brand_id';
  protected $guarded = ['brand_id'];

  public function fleetBrandModels() {
    return $this->hasMany(FleetBrandModel::class, 'brand_id', 'brand_id')
    ->whereNull('deleted_at');
  }

}
