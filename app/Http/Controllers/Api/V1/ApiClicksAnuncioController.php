<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clicks_anuncios;

class ApiClicksAnuncioController extends Controller
{
    private $clicksAnuncios;
    
    public function __construct(Clicks_anuncios $clicksAnunciosGet) {
        $this->clicksAnuncios = $clicksAnunciosGet;
    }
    

    public function store(Request $request)
    {
        $click = $this->clicksAnuncios;
        
        $data = $request->all();
        $validate = validator($data, $click->rules());
        if($validate->fails()){
            $messagens = $validate->messages();
            return response()->json(['error' => $messagens], 422);
        }
        
        //usuario logado
        $user = JWTAuth::parseToken()->authenticate();
        $data['user_id'] = $user->id;
        
        if( !$insert = $click->create($data) ){
            return response()->json(['error' => 'error_insert'], 500);
        }
        
        return response()->json(['data' => $insert], 201);
    }
    
    public function checkClickIp($ip) {
        if ( !filter_var($ip, FILTER_VALIDATE_IP) ){ //IPV4
            return response()->json(['error' => 'invalid_ip'], 500);
        }
        
        if ( !$click = $this->clicksAnuncios->where('ip_click', $ip)->orderBy('created_at', 'asc')->first() ){
            return response()->json(['error' => 'not_found'], 404);
        }
        
        return response()->json(['data' => $click]);
    }
}
