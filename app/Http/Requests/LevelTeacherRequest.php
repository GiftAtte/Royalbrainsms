<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LevelTeacherRequest extends FormRequest
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
            'level_arm_id'=>'required|integer',
            'employee_id'=>'required|integer',
            'created_by'=>'nullable'
        ];
    }
}
