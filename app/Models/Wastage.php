<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wastage extends Model
{
    protected $fillable = [

        'wastage_no',
        'material_id',
        'quantity',
        'reason',
        'wastage_date',
        'recorded_by',
        'reference_type',
        'reference_id',

    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
