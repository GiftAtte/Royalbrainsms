<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubLearningDomainRequest extends FormRequest
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
            'learning_domain_id'=>'required|integer',
            'sub_domains'=>'required|array',
            'sub_domains.*name'=>'required|integer',

           
        ];
    }
}
