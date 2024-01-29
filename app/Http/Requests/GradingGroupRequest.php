<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradingGroupRequest extends FormRequest
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
          'grades'=>'required|array',
          'title'=>['required',Rule::unique('grading_groups')->ignore($this->grading_group)],
          // grades
          'grades.*.min_score' => 'required|numeric',
          'grades.*.max_score' => 'required|numeric',
          'grades.*.grade' => 'required|string',
          'grades.*.desc' => 'required|string',

        ];
    }
}
