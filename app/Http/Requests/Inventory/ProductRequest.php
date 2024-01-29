<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    { return [
        'name'=>['required','string', Rule::unique('products')->ignore($this->product)],
        'product_code'=>['required', Rule::unique('products')->ignore($this->product)],
        'barcode'=>'nullable',
        'unit_price'=>'required',
        'product_image'=>'nullable',
        'stock'=>'nullable',
        'sku'=>'required',
        'category_id'=>'required',
        'created_by'=>'nullable',
        // unit_price
        'unit_price.*.unit' => 'required',
        'unit_price.*.skuQtyPerUnit' => 'required|numeric',
        'unit_price.*.price' => 'required|numeric',


       
    ];
    }
}
