<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LearningDomainAssessementRequest extends FormRequest
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
             'report_id'=>'required|integer',
             'learning_domain_id'=>'required|integer',
             'sub_learning_domain_id'=>'required|integer',
             'assessments'=>'required|array',
             'assessments.*.student_id'=>'required|integer',
        
        ];
    }
}
