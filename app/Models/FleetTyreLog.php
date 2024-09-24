<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetTyreLog extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_fleet_tyre_log';

  protected $primaryKey = 'log_id';
  protected $guarded = ['log_id'];

  public function fleet() {
    return $this->belongsTo(Fleet::class, 'fleet_id', 'fleet_id');
  }

  public function axle() {
    return $this->belongsTo(FleetAxle::class, 'fleet_axle_id', 'axle_id');
  }

}
