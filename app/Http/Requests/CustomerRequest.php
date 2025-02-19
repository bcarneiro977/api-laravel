<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; 

class CustomerRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $customerId = $this->route('customer'); 
    
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')->ignore($customerId, 'id'),
            ],
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'default_address_id' => [
                'nullable',
                'uuid',
                Rule::exists('addresses', 'id')->where(function ($query) use ($customerId) {
                    return $query->where('customer_id', $customerId);
                }),
            ],
        ];
    }
    
}
