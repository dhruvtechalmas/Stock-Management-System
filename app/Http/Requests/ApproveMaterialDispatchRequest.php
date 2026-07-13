<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveMaterialDispatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'material_request_id' => ['required','exists:material_requests,id',],

            'items' => ['required','array','min:1',],

            'items.*.request_item_id' => ['required','exists:material_request_items,id',],

            'items.*.dispatch_qty' => ['required','numeric','min:0',],
        ];
    }
}