<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialConsumption extends Model
{
    protected $fillable = [
        'material_dispatch_item_id',
        'material_id',
        'consumed_qty',
        'remaining_qty',
        'consumption_date',
        'recorded_by',
    ];

    protected $casts = [
        'consumption_date' => 'date',
        'consumed_qty' => 'decimal:3',
        'remaining_qty' => 'decimal:3',
    ];

    public function dispatchItem()
    {
        return $this->belongsTo(MaterialDispatchItem::class, 'material_dispatch_item_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

}
