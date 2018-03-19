<?php

namespace App\Http\Controllers\Admin\Estabelecimentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Vendor;
use App\Http\Requests\Admin\Estabelecimentos\EstabelecimentoStoreFormRequest;
use App\Http\Requests\Admin\Estabelecimentos\EstabelecimentoUpdateFormRequest;
use App\Models\Estabelecimentos;
use App\Models\Categorias_estabelecimentos;
use App\Models\Anuncios;
use Auth;

class EstabelecimentoController extends Controller
{
    private $estabelecimentos;
    private $categoriasEstabelecimentos;
    private $itensPorPagina;
    
    public function __construct(Estabelecimentos $estabelecimentosGet,
                                Categorias_estabelecimentos $categoriasEstabelecimentosGet) {
        $this->estabelecimentos = $estabelecimentosGet;
        $this->categoriasEstabelecimentos = $categoriasEstabelecimentosGet;
        $this->itensPorPagina = config('constantes.ITENS_POR_PAGINA');
    }
    
    public function index()
    {
        if(Auth::user()->is_admin){
            $estabelecimentos = $this->estabelecimentos->paginate($this->itensPorPagina);
        }else{
            $estabelecimentos = $this->estabelecimentos->where('user_id', '=', Auth::user()->id)->paginate($this->itensPorPagina);
        }
        
        $title = 'Meus Estabelecimentos';
        return view('admin.estabelecimentos.index', compact('title', 'estabelecimentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoriasEstabelecimentos = $this->categoriasEstabelecimentos->pluck('nome', 'id')->all();
        $title = 'Cadastrar Estabelecimento';
        
        return view('admin.estabelecimentos.cadastrar-editar', compact('title', 'categoriasEstabelecimentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstabelecimentoStoreFormRequest $request)
    {
        //Recuperar os dados do formulário
        $dataForm = $request->all();
        
        //Formatar dados para o DB
        $dataForm['exibir_telefone_fixo_clientes'] = ( !isset($dataForm['exibir_telefone_fixo_clientes']) ) ? 0 : 1;
        $dataForm['exibir_telefone_celular_clientes'] = ( !isset($dataForm['exibir_telefone_celular_clientes']) ) ? 0 : 1;
        $dataForm['telefone_celular_is_whatsapp'] = ( !isset($dataForm['telefone_celular_is_whatsapp']) ) ? 0 : 1;
        
        //Dados para preenchimento extra form
        $dataForm['user_id'] = Auth::user()->id;
       
        //salvar imagem perfil se houver
        if(isset($dataForm['image'])){
            $imagemSalva = Vendor::salvarImagemUpload($request, config('constantes.DESTINO_IMAGE_ESTABELECIMENTO'));
            if(!$imagemSalva['status']){
                return redirect()->route('admin.estab.cadastro')->with('messageReturn', ['status' => false, 'messages' => [$imagemSalva['return'],]]);
            }else{
                $dataForm['image'] = $imagemSalva['return'];
            }
        }
        
        //Cadastrar dados no BD
        $create = $this->estabelecimentos->create($dataForm);
        
        if ($create) {
            return redirect()->route('admin.estab')->with('messageReturn', ['status' => true, 'messages' => ['Cadastrado com sucesso.',]]);
        } else {
            return redirect()->route('admin.estab.cadastro')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao cadastrar.',]]);
        }
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
    public function edit($id)
    {
        //Recuperar dados do estabelecimento
        $estabelecimento = $this->estabelecimentos->find($id);
        
        //redirecionar se o item não existir
        if (!$estabelecimento) return redirect()->back();
        
        //usuario logado tem autorização?
        if(!$estabelecimento->userAuthorize()) return redirect()->back();
        
        $categoriasEstabelecimentos = $this->categoriasEstabelecimentos->pluck('nome', 'id')->all();

        $title = "Editar Estabelecimento: {$estabelecimento->nome}";
        
        return view('admin.estabelecimentos.cadastrar-editar', compact('title', 'estabelecimento', 'categoriasEstabelecimentos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EstabelecimentoUpdateFormRequest $request, $id)
    {
        //recuperar dados do formulario
        $dataForm = $request->all();
        
        //Recuperar dados do estabelecimento
        $estabelecimento = $this->estabelecimentos->find($id);
        
        //redirecionar se o item não existir
        if (!$estabelecimento) return redirect()->back();
        
        //usuario logado tem autorização?
        if(!$estabelecimento->userAuthorize()) return redirect()->back();
        
        //Formatar dados para o DB
        $dataForm['exibir_telefone_fixo_clientes'] = ( !isset($dataForm['exibir_telefone_fixo_clientes']) ) ? 0 : 1;
        $dataForm['exibir_telefone_celular_clientes'] = ( !isset($dataForm['exibir_telefone_celular_clientes']) ) ? 0 : 1;
        $dataForm['telefone_celular_is_whatsapp'] = ( !isset($dataForm['telefone_celular_is_whatsapp']) ) ? 0 : 1;
        
        //salvar imagem perfil se houver
        if(isset($dataForm['image'])){
            //Apagar imagem perfil antiga se houver (update only)
            if($estabelecimento->image != null){
                Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_ESTABELECIMENTO'), $estabelecimento->image);
            }
            $imagemSalva = Vendor::salvarImagemUpload($request, config('constantes.DESTINO_IMAGE_ESTABELECIMENTO'));
            if(!$imagemSalva['status']){
                return redirect()->route('admin.estab.cadastro')->with('messageReturn', ['status' => false, 'messages' => [$imagemSalva['return'],]]);
            }else{
                $dataForm['image'] = $imagemSalva['return'];
            }
        }
        
        $update = $estabelecimento->update($dataForm);
        
        if($update){
            return redirect()->route('admin.estab')->with('messageReturn', ['status' => true, 'messages' => ['Atualizado com sucesso.',]]);
        }else{
            return redirect()->route('admin.estab.editar', $id)->with('messageReturn', ['status' => false, 'messages' => ['Falha ao atualizar.',]]);
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
        //Recuperar dados do estabelecimento
        $estabelecimento = $this->estabelecimentos->find($id);
        
        //redirecionar se o item não existir
        if (!$estabelecimento) return redirect()->back();
        
        //Apagar imagem perfil antiga se houver (update only)
        if($estabelecimento->image != null){
            Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_ESTABELECIMENTO'), $estabelecimento->image);
        }
        //Apagar imagens de anuncios desse estabelecimento
        foreach (Anuncios::where('estabelecimento_id', '=', $estabelecimento->id)->get() as $anuncio) {
            if($anuncio->image != null){
                Vendor::apagarArquivo(Vendor::$DESTINO_IMAGE_ANUNCIO, $anuncio->image);
            }
        }
        
        $delete = $estabelecimento->delete();
        
        if($delete){
            return redirect()->route('admin.estab')->with('messageReturn', ['status' => true, 'messages' => ['Deletado com sucesso.',]]);
        }else{
            return redirect()->route('admin.estab')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
        }
    }
    
    public function search(Request $request) {
        $estabelecimentos = Vendor::searchByNome($request, $this->estabelecimentos, 'nome');
        if (is_string($estabelecimentos)){ //error
            return redirect()->route('admin.estab')->with('messageReturn', ['status' => false, 'messages' => [$estabelecimentos,]]);
        }else{
            $title = 'Meus Estabelecimentos';
            
            return view('admin.estabelecimentos.index', compact('title', 'estabelecimentos'));
        }
    }
}
