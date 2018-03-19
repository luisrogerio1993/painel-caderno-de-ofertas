<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comentarios_anuncios;
use JWTAuth;
use App\Http\Controllers\Api\V1\ApiVendorController;

class ApiComentariosAnuncioController extends Controller
{
    private $comentarios;
    private $itensPorPagina;
    
    public function __construct(Comentarios_anuncios $comentariosAnuncios) {
        $this->comentarios = $comentariosAnuncios;
        $this->itensPorPagina = config('constantes.ITENS_POR_PAGINA');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validate = validator($data, $this->comentarios->rules());
        if($validate->fails()){
            $messagens = $validate->messages();
            return response()->json(['error' => $messagens], 422);
        }
        
        $user = JWTAuth::parseToken()->authenticate();
        $data['id_usuario'] = $user->id;
        
        if( !$insert = $this->comentarios->create($data) ){
            return response()->json(['error' => 'error_insert'], 500);
        }
        
        return response()->json(['data' => $insert], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$comentario = $this->comentarios->find($id)){
            return response()->json(['error' => 'not_found'], 404);
        }
        return response()->json(['data' => $comentario]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validar requisição
        $data = $request->all();
        $validate = validator($data, $this->comentarios->rules());
        if($validate->fails()){
            $messagens = $validate->messages();
            return response()->json(['error' => $messagens], 422);
        }
        
        //recuperar comentário
        if(!$comentario = $this->comentarios->find($id)){
            return response()->json(['error' => 'not_found'], 404);
        }
        
        //usuario logado tem autorização?
        $user = JWTAuth::parseToken()->authenticate();

        if(!$comentario->userAuthorize($user)){
            return response()->json(['error' => 'not_authorize'], 404);
        }
        
        //data
        $data['id_usuario'] = $user->id;
        $data['id_anuncio'] = $id;
        
        if( !$update = $comentario->update($data) ){
            return response()->json(['error' => 'error_update'], 500);
        }
        
        return response()->json(['data' => $update], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$comentario = $this->comentarios->find($id)){
            return response()->json(['error' => 'not_found'], 404);
        }
        
        //usuario logado tem autorização?
        $user = JWTAuth::parseToken()->authenticate();
        if(!$comentario->userAuthorize($user)){
            return response()->json(['error' => 'not_authorize'], 404);
        }
        
        if(!$delete = $comentario->delete()){
            return response()->json(['error' => 'error_delete'], 500);
        }
        
        return response()->json(['data' => $delete]);
    }
    
    public function search(Request $request) {
        return ApiVendorController::searchByNome($request, $this->comentarios, 'comentario');
    }
}
