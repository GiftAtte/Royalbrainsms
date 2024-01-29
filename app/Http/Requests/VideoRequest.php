<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
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
            'video' => 'nullable|required_without:video_path|max:30000',
            'title'=>'required',
            'comment'=>'nullable',
            'level_arm_id'=>'required',
            'type'=>'required|string',
            'video_path' => 'nullable|required_without:video', // Adjust the rule for video_path as needed
        ];
    }
}
