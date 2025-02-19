<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        $productId = $this->route('product');

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('products', 'name')->ignore($productId, 'id'),
            ],
            'price' => 'required|numeric|min:0',
            'photo' => []
        ];
    }
}
