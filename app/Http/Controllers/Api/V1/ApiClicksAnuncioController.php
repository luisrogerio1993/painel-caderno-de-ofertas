<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clicks_anuncios;

class ApiClicksAnuncioController extends Controller
{

    public function store(Request $request)
    {
        $click = new Clicks_anuncios;
        
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
}
