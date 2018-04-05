<?php

namespace App\Http\Controllers\Admin\Perfil;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\Perfil\PerfilFormRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor;
use App\User;
use Auth;
use App\Models\Estabelecimentos;
use App\Models\Anuncios;
use App\Models\Clicks_anuncios;
use Carbon\Carbon;

class PerfilController extends Controller
{
    private $perfil;
    private $estabelecimentos;
    private $anuncios;
    private $clicksAnuncios;
    
    public function __construct(User $perfilGet, 
            Estabelecimentos $estabelecimentosGet, 
            Anuncios $anunciosGet,
            Clicks_anuncios $clicksAnunciosGet
            ) {
        $this->perfil = $perfilGet;
        $this->estabelecimentos = $estabelecimentosGet;
        $this->anuncios = $anunciosGet;
        $this->clicksAnuncios = $clicksAnunciosGet;
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        $title = 'Info: '.Auth::user()->name;
        $data = [
            'totalEstabelecimentos' => $this->estabelecimentos->where('user_id', '=', Auth::user()->id)->count(),
            'totalAnuncios' => $this->anuncios->where('user_id', '=', Auth::user()->id)->count(),
            'anunciosPadrao' => $this->anuncios->where('user_id', '=', Auth::user()->id)->where('tipo_anuncio', 1)->count(),
            'anunciosPremium' => $this->anuncios->where('user_id', '=', Auth::user()->id)->where('tipo_anuncio', 2)->count(),
            'anunciosProximidadeFisica' => $this->anuncios->where('user_id', '=', Auth::user()->id)->where('tipo_anuncio', 3)->count(),
            'totalClicksAnuncios' => $this->clicksAnuncios->where('user_id', '=', Auth::user()->id)->count(),
            'totalClicksAnunciosHoje' => $this->clicksAnuncios->whereDate('created_at', '=', Carbon::now()->toDateString())->where('user_id', '=', Auth::user()->id)->count(),
            'totalClicksPadrao' => $this->clicksAnuncios->where('nome_tipo_anuncio', 'Padrão')->where('user_id', '=', Auth::user()->id)->count(),
            'totalClicksPremium' => $this->clicksAnuncios->where('nome_tipo_anuncio', 'Premium')->where('user_id', '=', Auth::user()->id)->count(),
            'totalClicksProximidadeFisica' => $this->clicksAnuncios->where('nome_tipo_anuncio', 'Proximidade Física')->where('user_id', '=', Auth::user()->id)->count(),
            'mediaInvestimento' => number_format($this->clicksAnuncios->where('user_id', '=', Auth::user()->id)->avg('valor_click'), 2, ',', '.'),
            'totalInvestimento' => number_format($this->clicksAnuncios->where('user_id', '=', Auth::user()->id)->sum('valor_click'), 2, ',', '.'),
        ];
        
        return view('admin.perfil.info', compact('title', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $perfil = $this->perfil->find(Auth::user()->id);
        
        //redirecionar se o item não existir
        if (!$perfil) return redirect()->back();
        
        $title = 'Meu Perfil: '.Auth::user()->name;
        
        return view('admin.perfil.editar', compact('title', 'perfil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PerfilFormRequest $request)
    {
        //recuperar dados do formulario
        $dataForm = $request->all();
        
        //Recuperar dados do perfil ativo no momento
        $perfil = $this->perfil->find(Auth::user()->id);
        
        //redirecionar se o item não existir
        if (!$perfil) return redirect()->back();
        
        //Formatar dados para o DB
        $dataForm['password'] = bcrypt($dataForm['password']);
        
        //configurar UF e Cidade pelo CEP
        $resultCEP = Vendor::buscarCEP($dataForm['cep']);
        if(!is_array($resultCEP)){ //error
            return redirect()->back()->withInput()->withErrors(['cep' => ['CEP inválido']]);
        }
        
        //salvar imagem perfil se houver
        if(isset($dataForm['image'])){
            //Apagar imagem perfil antiga se houver (update only)
            if($perfil->image != null){
                Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_USUARIO'), $perfil->image);
            }
            $imagemSalva = Vendor::salvarImagemUpload($request, config('constantes.DESTINO_IMAGE_USUARIO'));
            if(!$imagemSalva['status']){
                return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => false, 'messages' => [$imagemSalva['return'],]]);
            }else{
                $dataForm['image'] = $imagemSalva['return'];
            }
        }
        
        $dataForm['cidade'] = $resultCEP['localidade'];
        $dataForm['uf'] = $resultCEP['ufID'];
        
        $update = $perfil->update($dataForm);
        
        if($update){
            return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => true, 'messages' => ['Atualizado com sucesso.',]]);
        }else{
            return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao atualizar.',]]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        if(Auth::user()->is_admin){
            return redirect()->route('admin.user')->with('messageReturn', ['status' => false, 'messages' => ['Administradores devem deletar usuários nesta página.',]]);
        }
        
        $perfil = $this->perfil->find(Auth::user()->id);
        
        //redirecionar se o item não existir
        if (!$perfil) return redirect()->back();
        
        Auth::logout();
        
        //Apagar imagem perfil antiga se houver (update only)
        if($perfil->image != null){
            Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_USUARIO'), $perfil->image);
        }
        //apagar imagens dos estabelecimentos do usuário
        if($perfil->getTotalEstabelecimentos() > 0){
            foreach ($perfil->getEstabelecimentos() as $estabelecimento) {
                Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_ESTABELECIMENTO'), $estabelecimento->image);
                
                //apagar imagem dos anuncios desse estabelecimento
                if($estabelecimento->getTotalAnuncios() > 0){
                    foreach ($estabelecimento->getAnuncios() as $anuncio) {
                        Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_ANUNCIO'), $anuncio->image);
                    }
                }
            }
        }
        
        //deletar usuário (deleta dependencias em cascata)
        $delete = $perfil->delete();
        
        if($delete){
            return redirect()->route('site.home');
        }else{
            return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
        }
    }
    
    public function destroyImage(Request $request) {
        if ( Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_USUARIO'), Auth::user()->image) ){
            //Recuperar dados do perfil ativo no momento
            $perfil = $this->perfil->find(Auth::user()->id);
            $perfil->image = null;
            if (!$perfil->save()){
                return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
            }
            return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => true, 'messages' => ['Deletada com sucesso.',]]);
        }
        return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
    }
}

