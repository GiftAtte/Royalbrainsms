<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;


class SaleRequest extends FormRequest
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
            'amount'=>'nullable',
            'product_details'=>'required|array',
            'sales_agent'=>'nullable',
            'customer_name'=>'nullable',
            'customer_phone'=>'nullable',
            'payment_type'=>'required',
            'invoice_number'=>'nullable',
            'discount'=>'nullable',
            'suspended_sale'=>'nullable',
            'total_amount'=>'required',
            'discounted_amount'=>'required',
            
            'product_details.*.id' => 'required',
            'product_details.*.name' => 'required',
            'product_details.*.quantity' => 'required',
            'product_details.*.unit' => 'required',
            'product_details.*.unit_price' => 'required',
            'product_details.*.amount' => 'required',
            'product_details.*.skuQtyPerUnit' => 'required',
            'product_details.*.skuQty' => 'required',

        ];
    }
}