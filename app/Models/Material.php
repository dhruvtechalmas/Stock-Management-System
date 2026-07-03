<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'material_name',
        'category',
        'unit',
        'minimum_stock',
        'description',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }
}
