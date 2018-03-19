<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Clicks_anuncios;
use JWTAuth;
use Carbon\Carbon;

class ApiPerfilController extends Controller
{
    
    private $user;
    private $clicksAnuncios;
    
    public function __construct(User $userGet, Clicks_anuncios $clicksAnunciosGet) {
        $this->user = $userGet;
        $this->clicksAnuncios = $clicksAnunciosGet;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $retorno = [];
        //recuperar dados do perfil
        $user = JWTAuth::parseToken()->authenticate();
        //recuperar informações da conta (se for anunciante)
        array_push($retorno, ['user' => $user]);
        
        if($user->isAnunciante()){
            //(quant. estabelecimentos / anuncios / cliques nos anuncios)
            $quantEstabelecimentos = $user->getTotalEstabelecimentos();
            $quantAnuncios = $user->getTotalAnuncios();
            $quantCliqueAnuncios = $this->clicksAnuncios->where('user_id', '=', $user->id)->count();
            $quantCliqueAnunciosHoje = $this->clicksAnuncios->whereDate('created_at', '=', Carbon::now()->toDateString())->where('user_id', '=', $user->id)->count();
        
            array_push($retorno, ['quantEstabelecimentos' => $quantEstabelecimentos]);
            array_push($retorno, ['quantAnuncios' => $quantAnuncios]);
            array_push($retorno, ['quantCliqueAnuncios' => $quantCliqueAnuncios]);
            array_push($retorno, ['quantCliqueAnunciosHoje' => $quantCliqueAnunciosHoje]);
        }
        
        return response()->json(['data' => $retorno]);
    }
}
