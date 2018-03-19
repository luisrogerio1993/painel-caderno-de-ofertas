<?php

namespace App\Http\Controllers\Admin\Anuncios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor;
use App\Http\Requests\Admin\Anuncios\AnuncioFormRequest;
use App\Models\Anuncios;
use App\Models\Categorias_estabelecimentos;
use App\Models\Tipos_anuncio;
use App\Models\Estabelecimentos;
use App\Models\Clicks_anuncios;
use Auth;

class AnuncioController extends Controller
{
    private $anuncio;
    private $categoriasEstabelecimento;
    private $estabelecimento;
    private $tiposAnuncio;
    private $itensPorPagina;
    
    public function __construct(Anuncios $anuncioGet,
                                Categorias_estabelecimentos $categoriasEstabelecimentoGet,
                                Tipos_anuncio $tiposAnuncioGet,
                                Estabelecimentos $estabelecimentoGet) {
        $this->anuncio = $anuncioGet;
        $this->estabelecimento = $estabelecimentoGet;
        $this->tiposAnuncio = $tiposAnuncioGet;
        $this->categoriasEstabelecimento = $categoriasEstabelecimentoGet;
        $this->itensPorPagina = config('constantes.ITENS_POR_PAGINA');
    }
    
    public function index()
    {
        if(Auth::user()->is_admin){
            $anuncios = $this->anuncio->paginate($this->itensPorPagina);
        }else{
            $anuncios = $this->anuncio->where('user_id', '=', Auth::user()->id)->paginate($this->itensPorPagina);
        }
        
        $title = 'Meus Anúncios';
        $tiposAnuncio = $this->tiposAnuncio::pluck('nome', 'id')->all();
        
        return view('admin.anuncios.index', compact('anuncios', 'title', 'tiposAnuncio'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Cadastrar Anuncio';
        $categoriasEstabelecimentos = $this->categoriasEstabelecimento->pluck('nome', 'id')->all();
        $meusEstabelecimentos = $this->estabelecimento->where('user_id', '=', Auth::user()->id)->get()->pluck('nome', 'id');
        $tiposAnuncio = Tipos_anuncio::pluck('nome', 'id')->all();

        return view('admin.anuncios.cadastrar-editar', compact('title', 'categoriasEstabelecimentos', 'tiposAnuncio', 'meusEstabelecimentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnuncioFormRequest $request)
    {
        //Recuperar os dados do formulário
        $dataForm = $request->all();
        
        //salvar imagem perfil se houver
        if(isset($dataForm['image'])){
            $imagemSalva = Vendor::salvarImagemUpload($request, config('constantes.DESTINO_IMAGE_ANUNCIO'));
            if(!$imagemSalva['status']){
                return redirect()->route('admin.anuncio.cadastro')->with('messageReturn', ['status' => false, 'messages' => [$imagemSalva['return'],]]);
            }else{
                $dataForm['image'] = $imagemSalva['return'];
            }
        }
        
        //Dados adicionais
        $dataForm['user_id'] = Auth::user()->id;
        
        //Cadastrar dados no BD
        $create = $this->anuncio->create($dataForm);
        
        if ($create) {
            return redirect()->route('admin.anuncio')->with('messageReturn', ['status' => true, 'messages' => ['Cadastrado com sucesso.',]]);
        } else {
            return redirect()->route('admin.anuncio.cadastro')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao cadastrar.',]]);
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
        $anuncio = $this->anuncio->find($id);
        
        //redirecionar se o item não existir
        if (!$anuncio) return redirect()->back();
        
        //usuario logado tem autorização?
        if(!$anuncio->userAuthorize()) return redirect()->back();
        
        $categoriasEstabelecimentos = $this->categoriasEstabelecimento->pluck('nome', 'id')->all();
        
        if (Auth::user()->is_admin == true){
            $meusEstabelecimentos = $this->estabelecimento->where('user_id', '=', $anuncio->user_id)->get()->pluck('nome', 'id');
        }else{
            $meusEstabelecimentos = $this->estabelecimento->where('user_id', '=', Auth::user()->id)->get()->pluck('nome', 'id');
        }
        
        
        $tiposAnuncio = Tipos_anuncio::pluck('nome', 'id')->all();
        
        //Formatar dados
        $anuncio['anuncio_valido_ate'] = date('Y-m-d', strtotime($anuncio->anuncio_valido_ate));
        
        $title = "Editar Anúncio: {$anuncio->nome}";
        
        return view('admin.anuncios.cadastrar-editar', compact('anuncio', 'categoriasEstabelecimentos', 'meusEstabelecimentos', 'tiposAnuncio', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AnuncioFormRequest $request, $id)
    {
        
        //recuperar dados do formulario
        $dataForm = $request->all();
        
        //Recuperar dados do estabelecimento
        $anuncio = $this->anuncio->find($id);
        
        //redirecionar se o item não existir
        if (!$anuncio) return redirect()->back();
        
        //usuario logado tem autorização?
        if(!$anuncio->userAuthorize()) return redirect()->back();
        
        //salvar imagem perfil se houver
        if(isset($dataForm['image'])){
            //Apagar imagem perfil antiga se precisar (update only)
            if($anuncio->image != null){
                Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_ANUNCIO'), $anuncio->image);
            }
            
            $imagemSalva = Vendor::salvarImagemUpload($request, config('constantes.DESTINO_IMAGE_ANUNCIO'));
            if(!$imagemSalva['status']){
                return redirect()->route('admin.anuncio.cadastro')->with('messageReturn', ['status' => false, 'messages' => [$imagemSalva['return'],]]);
            }else{
                $dataForm['image'] = $imagemSalva['return'];
            }
        }
        
        $update = $anuncio->update($dataForm);
        
        if($update){
            return redirect()->route('admin.anuncio')->with('messageReturn', ['status' => true, 'messages' => ['Atualizado com sucesso.',]]);
        }else{
            return redirect()->route('admin.anuncio.editar', $anuncio->id)->with('messageReturn', ['status' => false, 'messages' => ['Falha ao atualizar.',]]);
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
        //Recuperar dados do anuncio
        $anuncio = $this->anuncio->find($id);
        
        //redirecionar se o item não existir
        if (!$anuncio) return redirect()->back();
        
        //Apagar imagem perfil antiga se precisar (update only)
        if($anuncio->image != null){
            Vendor::apagarArquivo(config('constantes.DESTINO_IMAGE_ANUNCIO'), $anuncio->image);
        }
        
        $delete = $anuncio->delete();
        
        if($delete){
            return redirect()->route('admin.anuncio')->with('messageReturn', ['status' => true, 'messages' => ['Deletado com sucesso.',]]);
        }else{
            return redirect()->route('admin.anuncio')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao deletar.',]]);
        }
    }
    
    public function clicksAnuncios() {
        $clicksAnuncios = new Clicks_anuncios;
        $clicks = $clicksAnuncios->paginate($this->itensPorPagina);
        $title = 'Cliques';
        
        return view('admin.clicksAnuncio.index', compact('clicks', 'title'));
    }
    
    public function search(Request $request) {
        $anuncios = Vendor::searchByNome($request, $this->anuncio, 'nome');
        if (is_string($anuncios)){ //error
            return redirect()->route('admin.anuncio')->with('messageReturn', ['status' => false, 'messages' => [$anuncios,]]);
        }else{
            $title = 'Meus Anuncios';
            $tiposAnuncio = $this->tiposAnuncio::pluck('nome', 'id')->all();
            
            return view('admin.anuncios.index', compact('anuncios', 'title', 'tiposAnuncio'));
        }
    }
}
