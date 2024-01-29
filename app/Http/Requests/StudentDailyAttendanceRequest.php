<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentDailyAttendanceRequest extends FormRequest
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
            'attendance_date'=>'required|integer',
           'student_id'=>'nullable',
           'clock_in_time'=>'nullable',
            'clock_out_time'=>'nullable',
             'status'=>'nullable',
        ];
    }
}
