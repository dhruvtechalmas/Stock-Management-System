<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $fillable = [
        'material_id',
        'transaction_type',
        'reference_type',
        'reference_id',
        'qty_in',
        'qty_out',
        'balance_after',
        'transaction_date',
        'remarks',
        'created_by',
    ];

    public static function add(
        $materialId,
        $transactionType,
        $referenceType,
        $referenceId,
        $qtyIn,
        $qtyOut,
        $balanceAfter,
        $remarks = null
    ) {
        return self::create([
            'material_id' => $materialId,
            'transaction_type' => $transactionType,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'qty_in' => $qtyIn,
            'qty_out' => $qtyOut,
            'balance_after' => $balanceAfter,
            'transaction_date' => now(),
            'remarks' => $remarks,
            'created_by' => auth()->id(),
        ]);
    }

    protected $casts = [
        'transaction_date' => 'date',
        'qty_in' => 'decimal:3',
        'qty_out' => 'decimal:3',
        'balance_after' => 'decimal:3',
    ];

    /**
     * Material
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * User
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Related Model (Purchase, Dispatch, etc.)
     */
    public function reference()
    {
        return $this->morphTo();
    }
}
