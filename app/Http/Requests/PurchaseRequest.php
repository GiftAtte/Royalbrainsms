<?php

namespace App\Http\Requests;

use App\Rules\PurchaseRequesDetailsRule;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'created_by'=>"nullable",
            'department_id'=>"required|numeric",
            'store_id'=>"required|numeric",
            'product_details'=>["required"],
            'comment'=>"nullable",
            'request_status'=>"nullable",
            'request_type'=>"nullable",
            'payment_mode'=>"nullable",
            'vendor'=>'nullable',
            'amount'=>'nullable',
            'exp_delivery_date'=>"required",

            'product_details.*.item' => 'required',
            'product_details.*.uom' => 'required',
            'product_details.*.amount' => 'required',
            'product_details.*.quantity' => 'required',
            'product_details.*.status' => 'nullable',

        ];
    }
}
