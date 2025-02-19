<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'method' => 'required|string|max:255',
            'status' => 'required|string|in:pending,paid,canceled',
            'amount' => 'required|numeric|min:0'
        ];
    }
}
