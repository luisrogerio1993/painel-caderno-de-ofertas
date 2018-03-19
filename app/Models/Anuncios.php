<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Anuncios extends Model
{
    protected $table = "anuncios";
    
    //for else view anuncios e estab, ou model de nenhum anuncio cadastrado
    
    protected $fillable = [
        'image',
        'user_id', 
        'estabelecimento_id', 
        'nome', 
        'categoria_anuncio_id', 
        'descricao', 
        'valor_atual', 
        'valor_original', 
        'anuncio_valido_ate', 
        'tipo_anuncio',
        ];
    
    public function getTipoAnuncio() {
        return $this->hasOne(tipos_anuncio::class, 'id', 'tipo_anuncio');
    }
    
    public function getCategoriaAnuncio() {
        return $this->hasOne(Categorias_estabelecimentos::class, 'id', 'categoria_anuncio_id');
    }
    
    public function getEstabelecimentos() {
        return $this->hasOne(Estabelecimentos::class, 'id', 'estabelecimento_id');
    }
    
    public function userAuthorize() {
        if(Auth::user()->is_admin) return true;
        return $this->user_id == auth()->user()->id;
    }
}