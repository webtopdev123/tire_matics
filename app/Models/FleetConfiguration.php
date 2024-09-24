<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetConfiguration extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_setting_configuration';

  protected $primaryKey = 'configuration_id';
  protected $guarded = ['configuration_id'];
  
}
