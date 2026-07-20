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
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required.',
            'contanc_person.required' => 'Contact person is required.',
            'contact_person.string' => 'Contact person must be a string.',
            'contact_person.max' => 'Contact person name cannot exceed 255 characters.',
            'phone.nullable' => 'Phone number is not required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'email.email' => 'Please enter a valid email address.',
            'email.nullable' => 'Email address is not required.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'address.nullable' => 'Address is not required.',
            'address.string' => 'Address must be a string.',
            'is_active.required' => 'Please select a status.',
        ];
    }
}