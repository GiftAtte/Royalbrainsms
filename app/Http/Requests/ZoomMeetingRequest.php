<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZoomMeetingRequest extends FormRequest
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
             'is_live_class'=>'required|boolean',
             'title'=>'required|string',
             'meeting_date'=>'required|date',
             'start_time'=>'required|date',
             'duration'=>'required|string',
             'comment'=>'required|string',
        ];
    }
}
