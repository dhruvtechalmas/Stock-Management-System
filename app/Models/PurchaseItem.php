<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'material_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected function quantity(): Attribute
{
    return Attribute::make(
        get: fn ($value) => rtrim(rtrim($value, '0'), '.')
    );
}
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}