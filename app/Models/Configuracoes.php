<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracoes extends Model
{
    protected $table = "configuracoes";
    public $timestamps = false;
    
    protected $fillable = [
        'valor_anuncio_padrao', 
        'valor_anuncio_premium', 
        'valor_anuncio_proximidade_fisica', 
        'limite_anuncios_anunciante', 
        'limite_estabelecimentos_anunciante', 
        'texto_aviso', 
        'mostrar_aviso_para', 
        'configuracao_aviso'
    ];
}