<?php

namespace App\Http\Requests\Admin\Financeiro;

use Illuminate\Foundation\Http\FormRequest;

class RecargaFormRequest extends FormRequest
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
            'quantidade_item' => 'required|numeric|integer|min:3',
            'id_item' => 'required|numeric|integer|min:1',
        ];
    }
}
