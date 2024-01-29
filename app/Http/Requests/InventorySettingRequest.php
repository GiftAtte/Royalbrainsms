<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventorySettingRequest extends FormRequest
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
          'module'=>['required', Rule::unique('inventory_settings')->ignore($this->inventory_setting)],
          'setting_key'=>'required',
          'setting_value'=>'required',
          'store_id'=>'nullable',
        ];
    }
}
