<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Anuncios;
use App\Models\Estabelecimentos;
use App\Models\Comentarios_anuncios;
use App\Http\Controllers\Api\V1\ApiVendorController;
use App\Http\Controllers\Vendor;

class ApiAnunciosController extends Controller
{
    private $anuncio;
    private $estabelecimento;
    private $comentariosAnuncio;
    private $itensPorPagina;
    
    public function __construct(Anuncios $anuncioGet,
            Estabelecimentos $estabelecimentoGet,
            Comentarios_anuncios $comentariosAnunciosGet) {
        
        $this->anuncio = $anuncioGet;
        $this->estabelecimento = $estabelecimentoGet;
        $this->comentariosAnuncio = $comentariosAnunciosGet;
        $this->itensPorPagina = config('constantes.ITENS_POR_PAGINA');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $retorno = [];
        //6 anuncios premium, depois 9 anuncios padrão
        $limiteAnunciosPremiumPorPagina = 6;
        $limiteAnunciosPadraoPorPagina = 9;
        //pegar todos os registros disponiveis premium
        $anunciosPremium = $this->anuncio
                ->whereDate('anuncio_valido_ate', '>=', Carbon::now()->toDateString())
                ->where('tipo_anuncio', 2)
                ->orderByRaw('RAND()')
                ->take($limiteAnunciosPremiumPorPagina)
                ->get()
                ->toArray();
        //pegar todos os registros disponiveis padrao
        $anunciosPadrao = $this->anuncio
                ->whereDate('anuncio_valido_ate', '>=', Carbon::now()->toDateString())
                ->where('tipo_anuncio', 1)
                ->orderByRaw('RAND()')
                ->take($limiteAnunciosPadraoPorPagina)
                ->get()
                ->toArray();
        
        //modificar imagem -> link
        foreach ($anunciosPremium as $key => $anuncioPremium) {
            $anunciosPremium[$key] = Vendor::adicionarImagemLink($anuncioPremium, config('constantes.DESTINO_IMAGE_ANUNCIO'));
        }
        
        foreach ($anunciosPadrao as $key => $anuncioPadrao) {
            $anunciosPadrao[$key] = Vendor::adicionarImagemLink($anuncioPadrao, config('constantes.DESTINO_IMAGE_ANUNCIO'));
        }
        
        //rand 6 anuncios premium
        array_push($retorno, ['anunciosPremium' => $anunciosPremium]);
        
        //rand 6 anuncios padrao
        array_push($retorno, ['anunciosPadrao' => $anunciosPadrao]);
        
        return response()->json(['data' => $retorno]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $retorno = [];
        //recuperar anuncio
        if( !$anuncio = $this->anuncio->find($id) ){
            return response()->json(['error' => 'not_found'], 404);
        }
        //modificar imagem -> link
        $anuncio = Vendor::adicionarImagemLink($anuncio, config('constantes.DESTINO_IMAGE_ANUNCIO'));
        
        //recuperar dados do estabelecimento desse anuncio
        $estabelecimento = $this->estabelecimento->find($anuncio->estabelecimento_id);
        
        //modificar imagem -> link
        $estabelecimento = Vendor::adicionarImagemLink($estabelecimento, config('constantes.DESTINO_IMAGE_ESTABELECIMENTO'));
        
        //recuperar comentarios desse anuncio
        $comentarios = $this->comentariosAnuncio->where('id_anuncio', $anuncio->id)->get();
        
        array_push ($retorno, ['anuncio' => $anuncio]);
        array_push ($retorno, ['estabelecimento' => $estabelecimento]);
        array_push ($retorno, ['comentarios' => $comentarios]);
        
        return response()->json(['data' => $retorno]);
    }
    
    public function searchByNome(Request $request)
    {
        return ApiVendorController::searchByNome($request, $this->anuncio, 'nome');
    }
    
    public function searchByCategory(Request $request)
    {
        return ApiVendorController::searchByCategory($request, $this->anuncio, 'categoria_anuncio_id');
    }
}
