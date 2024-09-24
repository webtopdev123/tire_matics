<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TyreBrandModelSize extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_tyre_brand_model_size';

  protected $primaryKey = 'size_id';
  protected $guarded = ['size_id'];

}
