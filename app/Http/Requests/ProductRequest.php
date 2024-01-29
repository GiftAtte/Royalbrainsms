<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
    {

        return [
            'name'=>'required',
            'product_code'=>'required',
            'barcode'=>'nullable',
            'unit_price'=>'required',
            'product_image'=>'nullable',
            'stock'=>'nullable',
            'sku'=>'required',
            'category_id'=>'required',
            'created_by'=>'nullable',
            'created_by'=>'nullable',
            'id'=>'nullable',

            // unit_price
            'unit_price.*.unit' => 'required',
            'unit_price.*.skuQtyPerUnit' => 'required|numeric',
            'unit_price.*.price' => 'required|numeric',


           
        ];
    }
}
