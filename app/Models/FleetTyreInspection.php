<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetTyreInspection extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_fleet_tyre_inspection';

  protected $primaryKey = 'inspection_id ';
  protected $guarded = ['inspection_id '];

  public function tyre() {
    return $this->belongsTo(FleetTyre::class, 'tyre_id', 'tyre_id');
  }
  
}
