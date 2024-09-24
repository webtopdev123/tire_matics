<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nano_merchant_user_role';
    protected $fillable = ['role_name', 'merchant_id', 'created_at', 'updated_at', 'deleted_at'];
    protected $primaryKey = 'role_id';

    public function permissions()
    {
        return $this->hasMany(PermissionRole::class, 'role_id', 'role_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'merchant_id');
    }

}
