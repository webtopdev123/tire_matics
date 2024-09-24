<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetGoods extends Model {
  public $timestamps = false;
  use HasFactory;

  protected $table = 'nano_setting_goods';

  protected $primaryKey = 'goods_id';
  protected $guarded = ['goods_id'];

}
