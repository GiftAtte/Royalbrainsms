<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoTeacherCommentRequest extends FormRequest
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
            'max_average'=>'required',
        'min_average'=>'required',
        'comment'=>'required',
        ];
    }
}