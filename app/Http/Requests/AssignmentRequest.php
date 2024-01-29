<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
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
          'level_arm_id'=>'required|integer',
          'subject_id'=>'required|integer',
          'comment'=>'nullable',
          'content'=>'nullable|required_if:file,null|string',
          'file_path'=>'nullable',
          'file'=>'nullable|max:30000',
          'created_by'=>'nullable',
       
        ];
    }
}
