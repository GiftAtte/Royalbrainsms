<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CBTAssessmentRequest extends FormRequest
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
           "cbt_id"=>"required",
           'answer_sheet'=>"required|array",
           "answer_sheet.*.question_id"=>"required|integer",
           "answer_sheet.*.option_id"=>"nullable",
        ];
    }
}
