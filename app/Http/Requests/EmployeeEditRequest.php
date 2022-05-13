<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            'leaving' => 'nullable|date|date_format:Y/m/d',
            'manager' => 'boolean'
        ];
    }

    public function attributes()
    {
        return[
            'leaving' => '退社日',
        ];
    }

    public function messages()
    {
        return[
            'leaving.date_format' => '退社日は ○○○○/○○/○○ という形で指定してください。',
        ];
    }
}
