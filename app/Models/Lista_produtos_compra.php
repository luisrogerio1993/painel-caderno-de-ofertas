<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produtos_loja;

class Lista_produtos_compra extends Model
{
    protected $table = "lista_produtos_compras";
    public $timestamps = false;
    
    protected $fillable = ['compra_id', 'produto_id', 'valor_unitario', 'quantidade'];
    
    public function getNomeProduto($id) {
        $produto = Produtos_loja::where('id', '=', $id)->get()->first();
        return $produto['nome'];
    }
}
