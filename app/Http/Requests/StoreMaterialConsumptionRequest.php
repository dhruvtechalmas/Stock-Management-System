<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialConsumptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Important: false will always return 403 Forbidden
        return true;
    }

    public function rules(): array
    {
        return [
            'material_dispatch_item_id' => ['required','integer','exists:material_dispatch_items,id',],

            'material_id' => ['required','integer','exists:materials,id',],

            'consumed_qty' => ['required','numeric','min:0.001',],

            'consumption_date' => ['required','date','before_or_equal:today',],
        ];
    }

    public function messages(): array
    {
        return [
            'material_dispatch_item_id.required' =>'The dispatch item is required.',
            'material_dispatch_item_id.exists' =>'The selected dispatch item does not exist.',

            'material_id.required' =>'The material is required.',
            'material_id.exists' =>'The selected material does not exist.',

            'consumed_qty.required' =>'Please enter the consumed quantity.',
            'consumed_qty.numeric' =>'The consumed quantity must be a number.',

            'consumed_qty.min' =>'The consumed quantity must be at least 0.001.',

            'consumption_date.required' =>'Please select the consumption date.',
            'consumption_date.before_or_equal' =>'The consumption date cannot be a future date.',
        ];
    }
}