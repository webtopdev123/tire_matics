<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'nano_merchant_permission';

  protected $fillable = ['permission_name', 'permission_code', 'permission_read', 'permission_create', 'permission_update', 'permission_delete', 'created_at', 'updated_at', 'deleted_at'];

  protected $primaryKey = 'permission_id';

  public function roles()
  {
    return $this->belongsToMany(Role::class, 'nano_merchant_user_permission_role', 'permission_id', 'role_id');
  }

  public function permission_role()
  {
    return $this->belongsTo(PermissionRole::class, 'permission_id', 'permission_id');
  }
}
