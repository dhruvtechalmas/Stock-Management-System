<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
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
            'material_category_id' => 'required|exists:material_categories,id',
            'unit' => 'required|in:Kg,Liter,Piece',
            'current_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'minimum_stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
           'status' => 'required|in:Active,Inactive',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'material_name.required' => 'Material name is required.',

            'material_category_id.required' => 'Material Category is required.',

            'unit.required' => 'Please select a unit.',
            'unit.in' => 'Invalid unit selected.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg,jpeg, png, webp.',
            'image.max' => 'The image size must not exceed 2MB.',

            'current_stock.required' => 'Current stock is required.',
            'current_stock.integer' => 'Current stock must be a number.',
            'current_stock.min' => 'Current stock cannot be negative.',

            'minimum_stock.required' => 'Minimum stock is required.',
            'minimum_stock.integer' => 'Minimum stock must be a number.',
            'minimum_stock.min' => 'Minimum stock cannot be negative.',

            'status.required' => 'Please select a status.',
        ];
    }
}