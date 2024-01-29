<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
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
            'id'=>'nullable',
            'name'=>'required|string',
            'website_address'=>'required|string',
            'email'=>'required|string',
            'address'=>'required|string',
            'state'=>'required|string',
            'country'=>'required|string',
            'email'=>'required|string',
            'phone_number'=>'required|string',
            'short_name'=>'required|string',
            'logo'=>'nullable',
        ];
    }
}
