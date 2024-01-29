<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SessionSettingRequest extends FormRequest
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
    'title'=>'required|string',
    'result_template_id'=>'required|integer',
    'grading_group_id'=>'required|integer',
    'authorizer_id'=>'required|integer',
    'score_header_id'=>'required|integer',
    'is_cummulative'=>'nullable|boolean',
    'score_computation_style'=>['required', Rule::in(["TOTAL_SCORES","AVERAGE_SCORES","MADONNA_CUSTOM"])],
    'is_attendance'=>'nullable|boolean',
    'is_authorized_comment'=>'nullable|boolean',
    'is_teacher_comment'=>'nullable|boolean',
    'is_progress_status'=>'nullable|boolean',
    'is_max_score'=>'nullable|boolean',
    'is_min_score'=>'nullable|boolean',
    'is_subject_position'=>'nullable|boolean',
    'is_arm_position'=>'nullable|boolean',
    'is_level_position'=>'nullable|boolean',
    'is_learning_domain'=>'nullable|boolean',
    'is_manual_authorized_comment'=>'nullable|boolean',
    'is_dob'=>'nullable|boolean',
    'is_gender'=>'nullable|boolean',
    'result_title'=>'nullable|string',
    'signature'=>'nullable|file',
    'authorized_signature'=>'nullable',
    'created_by'=>'nullable',
    'is_manual_teacher_comment'=>'nullable',
    'is_midterm'=>'nullable'
      
];
    }
}
