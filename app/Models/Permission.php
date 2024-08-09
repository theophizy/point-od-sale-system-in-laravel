<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'module_id'
    ];


    // Permission belongs to a module
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

     // Permission belongs to many roles
     public function roles()
     {
         return $this->belongsToMany(Role::class, 'role_permission');
     }

     protected static function boot()
    {
        parent::boot();

        static::deleting(function ($permission) {
            if ($permission->roles()->count() > 0) {
                throw new \Exception("Cannot delete this permission as it is currently assigned to one or more roles.");
            }
        });
    }
}
