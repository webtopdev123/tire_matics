<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRole extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'nano_merchant_user_permission_role';

  protected $fillable = ['permission_id', 'role_id', 'permission_role_read', 'permission_role_create', 'permission_role_update', 'permission_role_delete', 'created_at', 'updated_at', 'deleted_at'];

  protected $primaryKey = 'permission_role_id';

  public function permissions()
  {
    return $this->belongsToMany(Permission::class, 'nano_merchant_user_permission_role', 'permission_id', 'role_id');
  }

  public function permission()
  {
    return $this->belongsTo(Permission::class, 'permission_id', 'permission_id');
  }
}
