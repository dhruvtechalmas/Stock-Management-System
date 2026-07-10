<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialDispatchItem extends Model
{
    protected $fillable = [
        'material_dispatch_id',
        'material_request_item_id',
        'material_id',
        'dispatched_qty',
        'received_qty',
        'missing_qty',
    ];

    public function dispatch()
    {
        return $this->belongsTo(MaterialDispatch::class);
    }

    public function requestItem()
    {
        return $this->belongsTo(MaterialRequestItem::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}