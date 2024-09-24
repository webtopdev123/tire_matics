<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TyreBrandModel extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_tyre_brand_model';

  protected $primaryKey = 'tyre_model_id ';
  protected $guarded = ['tyre_model_id '];

}
