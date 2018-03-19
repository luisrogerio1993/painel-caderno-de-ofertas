<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Estabelecimentos;
use App\Http\Controllers\Api\V1\ApiVendorController;

class ApiEstabelecimentoController extends Controller
{
    private $estabelecimento;
    private $itensPorPagina;
    
    public function __construct(Estabelecimentos $estabelecimentoGet) {
        $this->estabelecimento = $estabelecimentoGet;
        $this->itensPorPagina = config('constantes.ITENS_POR_PAGINA');
    }
    
    public function show($id){
        $retorno = [];
        //recuperar estabelecimento
        if( !$estabelecimento = $this->estabelecimento->find($id) ){
            return response()->json(['error' => 'not_found'], 404);
        }
        //Quantidade de anuncios cadastrados
        $quantAnuncios = $estabelecimento->getTotalAnuncios();
        
        array_push ($retorno, ['estabelecimento' => $estabelecimento]);
        array_push ($retorno, ['quantAnuncios' => $quantAnuncios]);
        
        return response()->json(['data' => $retorno]);
    }
    
    public function searchByNome(Request $request)
    {
        return ApiVendorController::searchByNome($request, $this->estabelecimento, 'nome');
    }
    
    public function searchByCategory(Request $request)
    {
        return ApiVendorController::searchByCategory($request, $this->estabelecimento, 'categoria_estabelecimento');
    }
}
