<?php

namespace App\Http\Controllers\Admin\Configuracao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuracoes;
use App\Http\Requests\Admin\Configuracoes\ConfiguracaoUpdateFormRequest;

class ConfiguracaoController extends Controller
{
    
    private $configuracoes;
    
    public function __construct(Configuracoes $configuracoesGet) {
        $this->configuracoes = $configuracoesGet;
    }
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $title = 'Configurações';
        $configuraçõesMostrarAvisoPara = ['Usuários', 'Anunciantes', 'Usuários e Anunciantes', 'Administradores', 'Todos'];
        $configuraçõesConfiguracaoAviso = ['Desativada', 'Importante', 'Crítica'];
        $configurações = $this->configuracoes->find(1);

        //formatar valores para moeda
        $configurações->valor_anuncio_padrao = number_format( $configurações->valor_anuncio_padrao, 2, '.', '');
        $configurações->valor_anuncio_premium = number_format( $configurações->valor_anuncio_premium, 2, '.', '');
        $configurações->valor_anuncio_proximidade_fisica = number_format( $configurações->valor_anuncio_proximidade_fisica, 2, '.', '');
        
        return view('admin.configuracoes.editar', compact('title', 'configurações', 'configuraçõesMostrarAvisoPara', 'configuraçõesConfiguracaoAviso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ConfiguracaoUpdateFormRequest $request)
    {
        //recuperar dados do formulario
        $dataForm = $request->all();
        
        //Recuperar dados: configurações atuais
        $configuracoes = $this->configuracoes->find(1);
        
        //redirecionar se o item não existir
        if (!$configuracoes) return redirect()->back();
        
        $update = $configuracoes->update($dataForm);
        
        if($update){
            return redirect()->route('admin.config.editar')->with('messageReturn', ['status' => true, 'messages' => ['Atualizado com sucesso.',]]);
        }else{
            return redirect()->route('admin.config.editar')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao atualizar.',]]);
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
        //
    }
}
