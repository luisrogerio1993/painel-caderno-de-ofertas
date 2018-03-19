<?php

namespace App\Http\Controllers\Admin\ComentariosAnuncios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comentarios_anuncios;
use App\Http\Controllers\Vendor;

class ComentariosAnunciosController extends Controller
{
    private $itensPorPagina;
    private $comentarios;
    
    public function __construct(Comentarios_anuncios $comentariosAnunciosGet) {
        $this->comentarios = $comentariosAnunciosGet;
        $this->itensPorPagina = config('constantes.ITENS_POR_PAGINA');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comentarios = Comentarios_anuncios::paginate($this->itensPorPagina);
        $title = 'Comentários';

        return view('admin.comentariosAnuncios.index', compact('comentarios', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ( !$comentario = $this->comentarios->find($id) ){
            return redirect()->back();
        }
        
        $title = 'Comentário';

        return view('admin.comentariosAnuncios.editar', compact('title', 'comentario'));
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
        $this->validate($request, $this->comentarios->rulesPainel());
        
        $data = $request->all();
        
        //recuperar comentário
        if(!$comentario = $this->comentarios->find($id)){
            return redirect()->back();
        }
        
        //data
        $data['id_anuncio'] = $comentario->id_anuncio;
        $data['id_usuario'] = $comentario->id_usuario;
        $data['quantidade_estrelas_avaliacao'] = $comentario->quantidade_estrelas_avaliacao;
        
        $update = $comentario->update($data);
        
        if($update){
            return redirect()->route('admin.comentario')->with('messageReturn', ['status' => true, 'messages' => ['Atualizado com sucesso.',]]);
        }else{
            return redirect()->route('admin.comentario.editar', $id)->with('messageReturn', ['status' => false, 'messages' => ['Falha ao atualizar.',]]);
        }
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
             return redirect()->back();
        }
        
        $delete = $comentario->delete();
        
        if($delete){
            return redirect()->route('admin.comentario')->with('messageReturn', ['status' => true, 'messages' => ['Deletado com sucesso.',]]);
        }else{
            return redirect()->route('admin.comentario.editar', $id)->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
        }
    }
    
    public function search(Request $request) {
        $comentarios = Vendor::searchByNome($request, $this->comentarios, 'comentario');
        if (is_string($comentarios)){ //error
            return redirect()->route('admin.comentario')->with('messageReturn', ['status' => false, 'messages' => [$comentarios,]]);
        }else{
            $title = 'Comentários';
            return view('admin.comentariosAnuncios.index', compact('comentarios', 'title'));
        }
    }
}
