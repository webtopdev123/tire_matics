<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetBrandModel extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_setting_brand_model';

  protected $primaryKey = 'model_id';
  protected $guarded = ['model_id'];

}
