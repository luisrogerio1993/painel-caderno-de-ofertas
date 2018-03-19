<?php

namespace App\Http\Requests\Admin\Estabelecimentos;

use Illuminate\Foundation\Http\FormRequest;

class EstabelecimentoStoreFormRequest extends FormRequest 
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
    public function rules($id = '')
    {
        return [
            'image' => "image|mimes:jpeg,png,jpg,gif|max:2097152|unique:estabelecimentos",
            'nome' => 'required|unique:estabelecimentos',
            'categoria_estabelecimento' => 'required|numeric|min:1',
            'descricao_estabelecimento' => 'min:5|max:1000',
            'rua' => 'required|string|min:5|max:100',
            'numero' => 'numeric|required|min:1|max:10000',
            'complemento' => 'string|min:1|max:150',
            'bairro' => 'required|string|min:1|max:150',
            'cpf' => 'unique:estabelecimentos|required_if:cnpj,""|numeric|digits:11',
            'cnpj' => 'unique:estabelecimentos|required_if:cpf,""|numeric|digits:14',
            'ddd_telefone_fixo' => 'numeric|nullable|required_if:telefone_celular,""',
            'telefone_fixo' => 'numeric|nullable|unique:estabelecimentos|required_if:telefone_celular,""',
            'ddd_telefone_celular' => 'numeric|nullable|required_if:telefone_fixo,""',
            'telefone_celular' => 'numeric|nullable|unique:estabelecimentos|required_if:telefone_fixo,""',
        ];
    }
    
    public function messages() {
        return [
            /* Vá está em resources/lang/pt-BR/validation */
        ];
    }

}
