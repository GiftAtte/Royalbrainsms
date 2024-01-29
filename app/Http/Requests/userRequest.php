<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class userRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {  return [
            'name'=>"required|string",
            'username' => ['required',Rule::unique('users')->ignore($this->user)],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles'=>'required',
            'password' => 'required|string|min:4',
            'type'=>'required|integer',
            'photo'=>'nullable|string'

            // 'password' => 'required|string|min:6|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ];
    }
}
