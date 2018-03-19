<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Categorias_estabelecimentos;
use App\Http\Controllers\Controller;

class ApiCategoriaEstabelecimentosController extends Controller
{
    private $categoriasEstabelecimentos;
    
    public function __construct(Categorias_estabelecimentos $categoriasEstabelecimentosGet) {
        $this->categoriasEstabelecimentos = $categoriasEstabelecimentosGet;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //recuperar todos os estabelecimentos
        $categoriasEstabelecimento = $this->categoriasEstabelecimentos->all();
        
        return response()->json(['data' => $categoriasEstabelecimento]);
    }
}
