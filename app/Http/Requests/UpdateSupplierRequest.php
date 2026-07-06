<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',

            'phone' => 'nullable|digits:10',

            'email' => 'nullable|email|max:255',

            // 'gst_number' => 'nullable|string|max:20',

            'address' => 'required|string',

            'is_active' => 'required|boolean',
        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required.',

            'phone.required' => 'Phone number is required.',
            'phone.digits_between' => 'Phone number must be between 10 and 15 digits.',

            'email.email' => 'Please enter a valid email address.',

            'address.required' => 'Address is required.',

            'is_active.required' => 'Please select a status.',
        ];
    }
}