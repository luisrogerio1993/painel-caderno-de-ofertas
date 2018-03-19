<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Anuncios;

class Comentarios_anuncios extends Model
{
    protected $table = "comentarios_anuncios";
    protected $fillable = ['id_anuncio', 'id_usuario', 'comentario', 'quantidade_estrelas_avaliacao'];
    
    public function rules() {
        return [
            'id_anuncio' => 'required|numeric|min:1',
            'comentario' => 'required|string|min:3|max:1000',
            'quantidade_estrelas_avaliacao' => 'required|numeric|min:1|max:5',
        ];
    }
    
    public function rulesPainel() {
        return [
            'comentario' => 'required|string|min:3|max:1000',
        ];
    }
    
    public function userAuthorize($user) {
        return $this->id_usuario == $user->id;
    }
    
    public function getAnuncio() {
        return Anuncios::where('id', $this->id_anuncio)->first();
    }
    
    public function getUser() {
        return User::where('id', $this->id_usuario)->first();
    }
}
