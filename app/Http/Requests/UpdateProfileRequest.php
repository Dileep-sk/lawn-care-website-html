<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Or add logic if needed
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|max:255',
            'old_password' => 'required|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required' => 'Old password is required',
            'new_password.confirmed' => 'New password confirmation does not match',
        ];
    }
}
