<?php

namespace App\Http\Controllers\Admin\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\AdminUserFormRequest;
use App\Http\Controllers\Vendor;
use App\User;

class UserController extends Controller {

    private $itensPorPagina;
    private $perfil;
    
    public function __construct(User $perfilGet) {
        $this->perfil = $perfilGet;
        $this->itensPorPagina = config('constantes.ITENS_POR_PAGINA');
    }

    public function index() {
        $users = User::paginate($this->itensPorPagina);
        $title = 'Usuários';

        return view('admin.users.index', compact('users', 'title'));
    }
    
    public function edit($id) {
        $user = $this->perfil->find($id);
        
        //redirecionar se o item não existir
        if (!$user) return redirect()->back();
        
        $title = 'Perfil do usuário: '.$user->name;

        return view('admin.perfil.editar', compact('title', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUserFormRequest $request, $id) {
        
        //recuperar dados do formulario
        $dataForm = $request->all();
        
        //Recuperar dados do perfil ativo no momento
        $perfil = $this->perfil->find($id);
        
        //redirecionar se o item não existir
        if (!$perfil) return redirect()->back();
        
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
                return redirect()->route('admin.user.editar', $id)->with('messageReturn', ['status' => false, 'messages' => [$imagemSalva['return'],]]);
            }else{
                $dataForm['image'] = $imagemSalva['return'];
            }
        }
        $dataForm['cidade'] = $resultCEP['localidade'];
        $dataForm['uf'] = $resultCEP['ufID'];
        
        $update = $perfil->update($dataForm);
        
        if($update){
            return redirect()->route('admin.user')->with('messageReturn', ['status' => true, 'messages' => ['Atualizado com sucesso.',]]);
        }else{
            return redirect()->route('admin.user.editar', $id)->with('messageReturn', ['status' => false, 'messages' => ['Falha ao atualizar.',]]);
        }
    }
    
    public function destroy($id)
    {
        
        $user = $this->perfil->find($id);
        
        //redirecionar se o item não existir
        if (!$user) return redirect()->back();
        
        //Apagar imagem perfil antiga se houver (update only)
        if($user->image != null){
            Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_USUARIO'), $user->image);
        }
        //apagar imagens dos estabelecimentos do usuário
        if($user->getTotalEstabelecimentos() > 0){
            foreach ($user->getEstabelecimentos() as $estabelecimento) {
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
        $delete = $user->delete();
        
        if($delete){
            return redirect()->route('admin.user')->with('messageReturn', ['status' => true, 'messages' => ['Deletado com sucesso.',]]);
        }else{
            return redirect()->route('admin.user.editar', $id)->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
        }
    }
    
    public function search(Request $request) {
        $users = Vendor::searchByNome($request, $this->perfil, 'name');
        if (is_string($users)){ //error
            return redirect()->route('admin.user')->with('messageReturn', ['status' => false, 'messages' => [$users,]]);
        }else{
            $title = 'Usuários';

            return view('admin.users.index', compact('users', 'title'));
        }
    }
    
    public function destroyImage(Request $request) {
        //get usuario a editar
        if(! $perfil = $this->perfil->find($request->only('id')['id']) ){
            return redirect()->back();
        }
        if ( Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_USUARIO'), $perfil->image) ){
            $perfil->image = null;
            if (!$perfil->save()){
                return redirect()->route('admin.user.editar', $request->only('id')['id'])->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
            }
            return redirect()->route('admin.user.editar', $request->only('id')['id'])->with('messageReturn', ['status' => true, 'messages' => ['Deletada com sucesso.',]]);
        }
        return redirect()->route('admin.user.editar', $request->only('id')['id'])->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
    }
}
