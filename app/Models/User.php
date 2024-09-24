<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $table = 'nano_merchant_user';

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'name',
    'password',
    'remember_token',
    'role_id',
    'merchant_id',
    'status',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'password' => 'hashed',
  ];

  public function roles()
  {
    return $this->hasMany(Role::class, 'role_id', 'role_id');
  }

  public function role()
  {
    return $this->belongsTo(Role::class, 'role_id', 'role_id');
  }

  public function merchant()
  {
    return $this->belongsTo(Merchant::class, 'merchant_id', 'merchant_id');
  }
}
