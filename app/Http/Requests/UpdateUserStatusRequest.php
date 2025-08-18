<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Change as needed for authorization logic
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:0,1',
        ];
    }
}
