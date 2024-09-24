<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TyreBrand extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_tyre_brand';

  protected $primaryKey = 'brand_id';
  protected $guarded = ['brand_id'];

  public function tyreBrandModels() {
    return $this->hasMany(TyreBrandModel::class, 'brand_id', 'brand_id')
    ->whereNull('deleted_at');
  }

}
