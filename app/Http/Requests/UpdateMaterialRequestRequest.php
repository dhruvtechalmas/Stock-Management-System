<?php

namespace App\Http\Requests;

use App\Models\Material;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMaterialRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
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

            'request_date' => ['required', 'date'],

            'remarks' => ['nullable', 'string', 'max:500'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.material_id' => ['required', 'exists:materials,id'],

            'items.*.requested_qty' => ['required', 'numeric', 'gt:0'],

            'status' => ['nullable', 'in:pending,approved,rejected'],

        ];
    }

    /**
     * Custom Validation
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (! $this->has('items')) {
                return;
            }

            foreach ($this->items as $index => $item) {

                if (
                    empty($item['material_id']) ||
                    empty($item['requested_qty'])
                ) {
                    continue;
                }

                $material = Material::find($item['material_id']);

                if (! $material) {
                    continue;
                }

                if ($item['requested_qty'] > $material->current_stock) {

                    $validator->errors()->add(
                        "items.$index.requested_qty",
                        "Requested quantity for {$material->material_name} cannot exceed available stock ({$material->current_stock} {$material->unit})."
                    );

                }
            }
        });
    }

    protected function failedValidation(Validator $validator)
{
    throw new HttpResponseException(
        redirect()
            ->back()
            ->withErrors($validator)
            ->withInput()
            ->with('edit_material_request_id', $this->route('material_request')->id)
    );
}
    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'request_date.required' => 'Request date is required.',
            'request_date.date' => 'Invalid request date.',

            'items.required' => 'Please add at least one material.',
            'items.array' => 'Invalid material list.',
            'items.min' => 'Please add at least one material.',

            'items.*.material_id.required' => 'Please select a material.',
            'items.*.material_id.exists' => 'Selected material does not exist.',

            'items.*.requested_qty.required' => 'Requested quantity is required.',
            'items.*.requested_qty.numeric' => 'Requested quantity must be numeric.',
            'items.*.requested_qty.gt' => 'Requested quantity must be greater than zero.',

            'status.nullable' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',

        ];
    }
}
