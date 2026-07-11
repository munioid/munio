<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }
    
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'last_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed', 'required_with:last_password'],
            'new_password_confirmation' => ['nullable', 'string', 'required_with:new_password']
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib di isi.',
            'email' => 'Format email tidak valid.',
            'new_password.min' => 'Password baru minimal harus :min karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
            'new_password.required_with' => 'Password baru wajib diisi.',
            'new_password_confirmation.required_with' => 'Konfirmasi Password baru wajib diisi.'
        ];
    }
}
