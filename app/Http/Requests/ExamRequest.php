<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamRequest extends FormRequest
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
            'level_arm_id'=>'required',
            'level_id'=>'required|integer',
            'duration'=>'required|integer',
            'comment'=>'required',
            'valid_till'=>'required|date',
            'venue'=>'required|string',
            'is_published'=>'nullable',
            'questions_per_page'=>'required|integer',
            'type'=>['required', Rule::in(["MULTI_CHOICE","THEORY"])],
             ];
    }
}
