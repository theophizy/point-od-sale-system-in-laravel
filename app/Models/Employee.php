<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
   
    use HasFactory;
    protected $fillable = [
        'other_name',
        'address',
        'guarantor_name',
        'guarantor_address',
        'guarantor_phone',
        'guarantor_place_work',
        'contact_person_name',
        'contact_person_address',
        'contact_person_phone'
    ];

    public function admin()
{
    return $this->belongsTo(Admin::class, 'admin_id','id');
}
}

