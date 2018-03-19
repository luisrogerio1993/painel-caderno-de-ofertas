<?php

namespace App\Http\Requests\Admin\AdminUser;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserFormRequest extends FormRequest
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
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2097152|unique:users',
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:191',
            'uf' => 'required|numeric|digits_between:1,3',
            'cidade' => 'required|string|min:3|max:100',
            'cep' => 'required|numeric|digits:8',
        ];
    }
}