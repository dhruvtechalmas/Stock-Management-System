<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|digits:10',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'email.email' => 'Please enter a valid email address.',
            'is_active.required' => 'Please select a status.',
        ];
    }
}