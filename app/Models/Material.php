<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'material_name',
        'material_category_id',
        'unit',
        'image',
        'current_stock',
        'minimum_stock',
        'description',
        'status',
    ];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function dispatchItems()
    {
        return $this->hasMany(MaterialDispatchItem::class);
    }

    public function consumptions()
    {
        return $this->hasMany(MaterialConsumption::class);
    }

    public function wastages()
    {
        return $this->hasMany(Wastage::class);
    }

    public function stockLedgers()
    {
        return $this->hasMany(StockLedger::class);
    }

    public function requestItems()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }
}
