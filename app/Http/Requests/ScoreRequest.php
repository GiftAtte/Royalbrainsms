<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScoreRequest extends FormRequest
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
            'subject_id'=>'required|integer',
            'level_arm_id'=>'required|integer',
            'scores'=>'required|array',
            'scores.*.student_id'=>'required|integer',
            'scores.*.ca_scores'=>'array',
            'exam'=>'nullable|numeric',
            'mid_term_score'=>'nullable|numeric',
            'compute_summary'=>'nullable',
        ];
    }
}