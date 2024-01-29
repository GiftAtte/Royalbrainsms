<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
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
            'basic' => 'required',
            'earnings' => 'nullable',
            'deductions' => 'nullable',
            'amount' => 'nullable',
            'salary_date' => 'required',
            'description' => 'nullable',
            'employee_id' => 'required',
            'generated_by' => 'nullable'
        ];
    }
}
