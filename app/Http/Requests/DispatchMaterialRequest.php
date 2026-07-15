<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class DispatchMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'material_dispatch_id' => ['required','exists:material_dispatches,id',],

            'items' => ['nullable','array',],

            'items.*.id' => ['nullable','exists:material_dispatch_items,id',],

        ];
    }
}