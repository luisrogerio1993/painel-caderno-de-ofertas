<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Estabelecimentos extends Model
{
    protected $table = "estabelecimentos";
    
    protected $fillable = [
        'image',
        'user_id',
        'nome', 
        'categoria_estabelecimento', 
        'descricao_estabelecimento', 
        'rua', 
        'numero', 
        'complemento', 
        'bairro',
        'cpf',
        'cnpj',
        'ddd_telefone_fixo',
        'telefone_fixo',
        'ddd_telefone_celular',
        'telefone_celular',
        'exibir_telefone_fixo_clientes',
        'exibir_telefone_celular_clientes',
        'telefone_celular_is_whatsapp',
    ];
    
    public function getCategoria() {
        return $this->hasOne(Categorias_estabelecimentos::class, 'id', 'categoria_estabelecimento');
    }
    
    public function getUsuario() {
        return $this->hasOne(App\User::class, 'id', 'user_id');
    }
    
    public function getTotalAnuncios() {
        return Anuncios::where('estabelecimento_id', '=', $this->id)->count();
    }
    
    public function getAnuncios() {
        return Anuncios::where('estabelecimento_id', '=', $this->id)->get();
    }
    
    public function userAuthorize() {
        if(Auth::user()->is_admin) return true;
        return $this->user_id == auth()->user()->id;
    }
}
