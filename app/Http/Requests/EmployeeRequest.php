<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
        'first_name'=>'required',
        'surname'=>'required',
        'email'=>['required','email', Rule::unique('employees')->ignore($this->employee)],
        'gender'=>'required',
        'roles'=>'required',
        'user'=>'nullable',
        'phone_number'=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|max:15',
        'hired_date'=>'nullable',
        'address'=>'nullable',
        'nationality'=>'nullable',
        'state'=>'nullable',
        'local_government'=>'nullable',
        'designation_id'=>'required',
        'marital_status'=>'nullable',
        'middle_name'=>'nullable',
        'dob'=>'nullable',
        'bank_name'=>'nullable',
        'account_number' => 'nullable|regex:/^[0-9]+$/',
        'account_number'=>'nullable',
        'bank_identifier_code'=>'nullable',
        'salary'=>'nullable',
        'salary_type'=>'nullable',
        'tax_payer_id'=>'nullable',
        'photo'=>'nullable'
        ];
    }



}
