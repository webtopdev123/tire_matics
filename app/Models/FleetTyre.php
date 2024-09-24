<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetTyre extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_fleet_tyre';

  protected $primaryKey = 'tyre_id';
  protected $guarded = ['tyre_id'];

  public function tyreBrand() {
    return $this->belongsTo(TyreBrand::class, 'tyre_brand_id', 'brand_id');
  }

  public function tyreBrandModel() {
    return $this->belongsTo(TyreBrandModel::class, 'tyre_model_id', 'model_id');
  }

  public function tyreBrandModelSize() {
    return $this->belongsTo(TyreBrandModelSize::class, 'tyre_size_id', 'size_id');
  }
}
