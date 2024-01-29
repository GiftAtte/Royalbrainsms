<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentCertificateRequest extends FormRequest
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
            'certificate_id'=>'required',
             'students'=>'required|array',
             'students.*.student_id'=>'required|integer',
             'students.*.certificate_number'=>['required', Rule::unique('student_certificates')->ignore($this->student_certificates)],
        ];
    }
}
