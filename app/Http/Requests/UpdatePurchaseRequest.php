<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'supplier_id' => 'required|exists:suppliers,id',

            'invoice_no' => 'nullable|string|max:100',

            'purchase_date' => 'required|date',

            'remarks' => 'nullable|string|max:500',

            'items' => 'required|array|min:1',

            'items.*.material_id' => 'required|exists:materials,id',

            'items.*.quantity' => 'required|numeric|gt:0', // Greater than 0

            'items.*.unit_price' => 'required|numeric|gt:0', // Greater than 0
           
        ];
    }

    public function messages(): array
    {
        return [

            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'Selected supplier is invalid.',

            'purchase_date.required' => 'Purchase date is required.',

            'items.required' => 'Please add at least one purchase item.',
            'items.array' => 'Purchase items format is invalid.',
            'items.min' => 'Please add at least one material.',

            'items.*.material_id.required' => 'Please select a material.',
            'items.*.material_id.exists' => 'Selected material is invalid.',

            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.numeric' => 'Quantity must be numeric.',
            'items.*.quantity.gt' =>'Quantity must be greater than zero.',

            'items.*.unit_price.required' => 'Unit price is required.',
            'items.*.unit_price.numeric' => 'Unit price must be numeric.',
            'items.*.unit_price.gt' => 'Unit price must be greater than zero.',
        ];
    }
}