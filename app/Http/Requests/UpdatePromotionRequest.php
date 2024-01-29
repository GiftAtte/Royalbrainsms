<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromotionRequest extends FormRequest
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
            'students'=>'required|array',
            'students.*.result_id'=>'required|integer',
            'students.*.is_promoted'=>'required|boolean',
            'student.*.progress_comment'=>'nullable|string'
        ];
    }
}
