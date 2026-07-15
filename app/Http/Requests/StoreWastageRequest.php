<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWastageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'material_dispatch_item_id' => 'required|exists:material_dispatch_items,id',
            'quantity' => 'required|numeric|min:0.001',
            'reason' => 'required|string|max:255',
            'wastage_date' => 'required',
            'date',
        ];
    }

    public function messages(): array
    {
        return [
            'material_dispatch_item_id.required' => 'Please select a Material Dispatch Item.',
            'material_dispatch_item_id.exists' => 'Selected Material Dispatch Item is invalid.',
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be numeric.',
            'quantity.min' => 'Quantity must be greater than zero.',
            'reason.required' => 'Reason is required.',
            'wastage_date.required' => 'Wastage date is required.',
        ];
    }
}