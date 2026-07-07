<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'purchase_no',
        'supplier_id',
        'invoice_no',
        'purchase_date',
        'total_amount',
        'remarks',
        'created_by',
    ];

    // protected $casts = [
    //     'purchase_date' => 'date',
    // ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}