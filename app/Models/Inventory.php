<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_id', 'total_amount'
    ];

    public function product(){
        return $this->belongsToMany(Product::class)
        ->withPivot('quantity_supplied', 'unit_cost', 'supplied_in', 'units_per_pack', 'packs_per_carton', 'percentage_unit_price')
        ->withTimestamps();
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function supplierPayments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($inventory) {
            if ($inventory->supplierPayments()->exists()) {
                throw new \Exception("Cannot delete the inventory due to associated records.");
            }
            if ($inventory->product()->exists()) {
                throw new \Exception("Cannot delete the inventory due to associated records.");
            }
        });
    }
}
