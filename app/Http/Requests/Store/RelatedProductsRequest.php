<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class RelatedProductsRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid:4'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get the default values for the request.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'limit' => $this->limit ?? 5,
            'page' => $this->page ?? 1,
        ]);
    }
}
