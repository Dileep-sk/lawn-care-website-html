<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can use any logic to authorize this request. For now, it allows all requests.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_name' => 'required|string|max:255',
            'design_no' => 'required|string|max:255',
            'image' => 'nullable|array',
            'quantity' => 'required|numeric',
            'order_no' => 'required|string|max:255',
            'status' => 'required|in:0,1,2,3',
            'matching_1' => 'nullable|string',
            'matching_2' => 'nullable|string',
            'matching_3' => 'nullable|string',
            'matching_4' => 'nullable|string',
            'matching_5' => 'nullable|string',
            'matching_6' => 'nullable|string',
            'matching_7' => 'nullable|string',
            'matching_8' => 'nullable|string',
            'message' => 'nullable|string',
        ];
    }
}
