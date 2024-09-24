<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetAxle extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_fleet_axle';

  protected $primaryKey = 'axle_id';
  protected $guarded = ['axle_id'];

  public function tyres() {
    return $this->hasMany(FleetTyre::class, 'axle_id', 'axle_id')
    ->whereNull('deleted_at');
  }

  public function l1(){
    return $this->belongsTo(FleetTyre::class, 'axle_position_l1', 'tyre_id');
  }



}
