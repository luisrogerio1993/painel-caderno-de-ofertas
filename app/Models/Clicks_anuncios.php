<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clicks_anuncios extends Model
{
    protected $table = "clicks_anuncios";
    protected $fillable = [
        'user_id', 
        'nome_usuario', 
        'nome_tipo_anuncio', 
        'nome_anuncio', 
        'nome_estabelecimento', 
        'ip_click',
        'valor_click',
        ];
    
    public function rules() {
        return [
            'nome_usuario' => 'required|string|min:3|max:191',
            'nome_tipo_anuncio' => 'required|string|min:3|max:191',
            'nome_anuncio' => 'required|string|min:3|max:191',
            'nome_estabelecimento' => 'required|string|min:3|max:191',
            'ip_click' => 'required|ip',
            'valor_click' => 'required|numeric',
        ];
    }
}
