<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'material_name' => 'required|string|max:255',

            'material_category' => 'required|string|max:255',

            'unit' => 'required|in:Kg,Liter,Piece',

            'minimum_stock' => 'required|integer|min:0',

            'description' => 'nullable|string|max:1000',

            'status' => 'required|boolean',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'material_name.required' => 'Material name is required.',

            'material_category.required' => 'Material category is required.',

            'unit.required' => 'Please select a unit.',
            'unit.in' => 'Invalid unit selected.',

            'minimum_stock.required' => 'Minimum stock is required.',
            'minimum_stock.integer' => 'Minimum stock must be a number.',
            'minimum_stock.min' => 'Minimum stock cannot be negative.',

            'status.required' => 'Please select a status.',
        ];
    }
}