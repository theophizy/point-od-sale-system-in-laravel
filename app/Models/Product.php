<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'weight',
        'manufacture_date',
        'expiry_date',
        'manufacturer',
        'sold_in',
        'dashboard_access',
    ];
    public function saleItem()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function admins()
    {
        return $this->belongsTo(Admin::class);
    }

    public function inventories(){
        return $this->belongsToMany(Inventory::class)
        ->withPivot('quantity_supplied', 'unit_cost', 'supplied_in', 'units_per_pack', 'packs_per_carton', 'percentage_unit_price')
        ->withTimestamps();
    }
    protected static function boot()
{
    parent::boot();

    static::deleting(function ($product) {
        if ($product->saleItem()->count() > 0) {
            throw new \Exception("Cannot delete this product because it has associated sales.");
        }
    });
}


}
