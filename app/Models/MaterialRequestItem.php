<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_request_id',
        'material_id',
        'requested_qty',
    ];

    // Item belongs to Material Request
    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    // Item belongs to Material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
