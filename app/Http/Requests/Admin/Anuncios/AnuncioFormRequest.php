<?php

namespace App\Http\Requests\Admin\Anuncios;

use Illuminate\Foundation\Http\FormRequest;

class AnuncioFormRequest extends FormRequest
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
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2097152|unique:anuncios',
            'estabelecimento_id' => 'required|numeric|min:1', 
            'nome' => 'required', 
            'categoria_anuncio_id' => 'required|numeric|min:1|digits_between:1,3', 
            'descricao' => 'required|string|min:5|max:1000',
            'valor_atual' => 'required|numeric',
            'valor_original' => 'required|numeric',
            'anuncio_valido_ate' => 'required|date|date_format:Y-m-d|after:now|before_or_equal:+1 week',
            'tipo_anuncio' => 'required|numeric',
        ];
    }
}
