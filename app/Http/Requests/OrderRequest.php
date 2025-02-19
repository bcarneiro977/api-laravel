<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'address_id'  => 'required|exists:addresses,id',
            'payment_id'  => 'required|exists:payments,id',
            'status'      => 'nullable|string',
            'products.*'  => 'required|array',  
            'products.*.product_id' => 'required|uuid|exists:products,id', 
            'products.*.quantity'   => 'required|integer|min:1',
            'products.*.price'      => 'required|numeric|min:0', 
        ];
    }
}
