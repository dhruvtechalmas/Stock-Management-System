<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_no',
        'requested_by',
        'approved_by',
        'request_date',
        'status',
        'remarks',
        'approved_at',
        'reject_reason',
    ];

    protected $casts = [
        'request_date' => 'date',
    ];

    // Material Request belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // Material Request has many Items
    public function items()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
