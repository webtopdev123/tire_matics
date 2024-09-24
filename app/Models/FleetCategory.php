<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetCategory extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_setting_category';

  protected $primaryKey = 'category_id';
  protected $guarded = ['category_id'];

}
