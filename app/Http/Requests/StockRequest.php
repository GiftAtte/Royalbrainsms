<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'product_id'=>'required',
            'quantity'=>'required',
            'manufacturer'=>'nullable',
            'destock'=>'nullable',
            'barcode'=>'nullable',

// Quantity
            'quantity.*.unit' => 'required',
            'quantity.*.value' => 'required',
// Unit price
            'unit_price.*.unit' => 'required',
            'unit_price.*.price' => 'required',

              ];
    }
}