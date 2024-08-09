<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_name',
        'supplier_address',
        'supplier_phone',
        'supplier_email',
        
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($supplier) {
            if ($supplier->inventories()->count() > 0) {
                throw new \Exception("Cannot delete the supplier due to associated records.");
            }

            if ($supplier->payments()->count() >0) {
                throw new \Exception("Cannot delete the supplier due to associated records.");
            }
        });
    }
}
