<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesAccountRequest extends FormRequest
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
            "sales_agent_id"=>'nullable',
             "start_date"=>'nullable',
             "end_date"=>'nullable',
        ];
    }
}
