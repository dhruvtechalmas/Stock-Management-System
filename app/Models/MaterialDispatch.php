<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialDispatch extends Model
{
    protected $fillable = [
        'dispatch_no',
        'material_request_id',
        'dispatched_by',
        'dispatched_at',
        'received_at',
        'received_by',
        'resolved_at',
        'resolved_by',
        'remarks',
        'status',
    ];

    public function request()
    {
        return $this->belongsTo(MaterialRequest::class, 'material_request_id');
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }

    public function items()
    {
        return $this->hasMany(MaterialDispatchItem::class);
    }
}