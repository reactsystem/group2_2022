<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCreateRequest extends FormRequest
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
            'id' => 'numeric|required|unique:users',
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'department' => 'required',
            'joining' => 'required|date|date_format:Y/m/d',
            'password' => 'required|min:8|confirmed',
            'manager' => 'boolean'
        ];
    }
    public function attributes()
    {
        return[
            'id' => '社員番号',
            'department' => '部署名',
            'joining' => '入社日',
        ];
    }
}
