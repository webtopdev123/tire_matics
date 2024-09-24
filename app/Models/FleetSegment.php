<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetSegment extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_setting_segment';

  protected $primaryKey = 'segment_id';
  protected $guarded = ['segment_id'];

}
