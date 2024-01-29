<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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

            'surname'=>'required',
            'first_name'=>'required',
            'middle_name'=>'nullable',
            'fullname'=>'nullable',
            'gender'=>'required|string',
            'dob'=>'nullable',
            'level_arm_id'=>'required|exists:level_arms,id',
            'guardian_id'=>'nullable',
            'address'=>'nullable',
            'phone_number'=>'nullable',
            'email'=>'nullable',
            'religion'=>'nullable',
            'nationality'=>'nullable',
            'state_of_origin'=>'nullable',
            'lga'=>'nullable',
            'mother_tongue'=>'nullable',
            'blood_group'=>'nullable',
            'state_of_origin'=>'nullable',
            'enrollment_date'=>'nullable',
            'graduation_date'=>'nullable',
            'deleted_at'=>'nullable',
            'created_by'=>'nullable',
            'emergency_number'=>'nullable',
            'photo'=>'nullable',
            'is_active'=>'nullable'
           
        ];
    }
}
