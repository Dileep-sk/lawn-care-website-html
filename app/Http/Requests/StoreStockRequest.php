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
            'mark_no' => 'required',
            'design_no' => 'required',
            'item_name' => 'required',
            'quantity' => 'required|integer',
            'stock_manage'=>'required'
        ];
    }
}
