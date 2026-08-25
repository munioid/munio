<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'event_id' => ['required', 'uuid'],
            'package_id' => ['nullable', 'uuid'],
            'name' => ['required'],
            'email' => ['required', 'email'],
            'quantity' => ['required', 'integer', 'min:1'],
            'user_id' => ['nullable', 'uuid', 'exists:users,id'],
        ];
    }
}
