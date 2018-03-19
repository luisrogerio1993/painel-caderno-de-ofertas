<?php

namespace App\Http\Requests\Admin\Configuracoes;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracaoUpdateFormRequest extends FormRequest
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
            'valor_anuncio_padrao' => 'required|numeric',
            'valor_anuncio_premium' => 'required|numeric',
            'valor_anuncio_proximidade_fisica' => 'required|numeric',
            'limite_anuncios_anunciante' => 'required|numeric|digits_between:1,3',
            'limite_estabelecimentos_anunciante' => 'required|numeric|digits_between:1,3',
            'texto_aviso' => 'nullable|max:10000',
            'mostrar_aviso_para' => 'required|numeric',
            'configuracao_aviso' => 'required|numeric',
        ];
    }
}