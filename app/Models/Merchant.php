<?php

namespace App\Models;

use App\Models\CashSale;
use Laravel\Passport\Token;
use App\Models\MerchantContent;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Merchant extends Model
{
  // use HasFactory, HasApiTokens, SoftDeletes;
  use HasFactory, HasApiTokens;

  protected $table = 'nano_merchant';

  protected $fillable = ['merchant_name', 'merchant_registration_number','merchant_url','merchant_whatsapp','merchant_facebook','merchant_instagram','merchant_tiktok','merchant_logo','merchant_priceshow','merchant_skincolor','created_at', 'updated_at', 'deleted_at','merchant_email','merchant_phone'];

  protected $primaryKey = 'merchant_id';

  // public function softDelete()
  // {
  //   // Update the deleted_at column with the current timestamp
  //   $this->deleted_at = now();
  //   $this->save();
  // }

  public function users()
  {
      return $this->hasMany(User::class, 'merchant_id' , 'merchant_id');
  }

  // Under this merchant role, this is use for deleted only
  public function roles()
  {
      return $this->hasMany(Role::class, 'merchant_id' , 'merchant_id');
  }

  public function content()
  {
    return $this->hasMany(MerchantContent::class, 'merchant_id');
  }
}
