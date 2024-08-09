<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

     // Role belongs to many admins
     public function admins()
     {
         return $this->belongsToMany(Admin::class, 'admin_role');
     }
 

    // Check if admin has a role
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

     // Role has many permissions
     public function permissions()
     {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
        // return $this->belongsToMany(Permission::class, 'role_permission');
     }

     protected static function boot()
    {
        parent::boot();

        static::deleting(function ($role) {
            if ($role->admins()->count() > 0) {
                throw new \Exception("Cannot delete this role as it is currently assigned to one or more users.");
            }
        });
    }
}
