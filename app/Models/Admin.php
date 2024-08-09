<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'dashboard_access',
    ];
    // Admin has many roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_role', 'admin_id', 'role_id');
       // return $this->belongsToMany(Role::class, 'admin_role');
    }
   
    public function permissions()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
    } 


    // Admin belongs to employee
    public function employee()
{
    return $this->hasOne(Employee::class, 'admin_id','id');
}

public function products()
{
    return $this->hasMany(Product::class);
}

public function sales()
{
    return $this->hasMany(Sale::class);
}





    
}
