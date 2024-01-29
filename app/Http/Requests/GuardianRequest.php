<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardianRequest extends FormRequest
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
            'title'=>'required',
            'name'=>'required',
            'phone_number'=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|max:15',
            'email'=>'required',
            'address'=>'nullable',
            'nationality'=>'nullable',
            'state'=>'nullable',
            'religion'=>'nullable',
            'lga'=>'nullable',
            'occupation'=>'nullable',
            'photo'=>'nullable',
            'deleted_at'=>'nullable',
            'created_by'=>'nullable',
            'students'=>'nullable|array'
        ];
    }
}
