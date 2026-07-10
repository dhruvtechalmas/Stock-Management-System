<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DispatchMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'material_dispatch_id' => ['required', 'exists:material_dispatches,id'],

            'items' => ['required', 'array'],

            'items.*.id' => ['required','exists:material_dispatch_items,id'],

            'items.*.dispatch_qty' => ['required','numeric','min:0.01'],
        ];
    }
}
