<?php

namespace App\Http\Requests\Admin\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class PerfilFormRequest extends FormRequest
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
            'password' => 'required|string|min:6|max:100',
            'cep' => 'required|numeric|digits:8',
        ];
    }
}
