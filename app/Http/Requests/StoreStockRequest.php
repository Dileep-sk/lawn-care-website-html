<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mark_no' => 'required|string',
            'design_no' => 'required|string',
            'item_name' => 'required|string',
            'quantity' => 'required|integer',
        ];
    }
}
