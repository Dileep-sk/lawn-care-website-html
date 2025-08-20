<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name'     => 'required|string',
            'date'              => 'required|date',
            'broker_name'       => 'nullable|string',
            'transport_company' => 'nullable|string',
            'design_no'         => 'nullable|string',
            'item_name'         => 'required|string',
            'quantity'          => 'required|numeric|min:0',
            'rate'              => 'required|numeric|min:0',
            'status'            => 'nullable|string',
            'message'           => 'nullable|string',
        ];
    }
}
