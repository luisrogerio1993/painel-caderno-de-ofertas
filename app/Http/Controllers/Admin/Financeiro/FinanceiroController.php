<?php

namespace App\Http\Controllers\Admin\Financeiro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Http\Requests\Admin\Financeiro\RecargaFormRequest;
use App\Models\Compras;

class FinanceiroController extends Controller
{
    private $itensPorPagina;
    
    public function __construct() {
        $this->itensPorPagina = config('constantes.ITENS_POR_PAGINA');
    }

    public function index()
    {
        $userCreditos = Auth::user()->credito_disponivel;
        
        $title = 'Meus Créditos';
        
        return view('admin.financeiro.index', compact('title', 'userCreditos'));
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
    public function show(Compras $compras)
    {
        $userCompras = $compras->where('user_id', '=', auth()->user()->id)->orderBy('created_at', 'desc')->paginate($this->itensPorPagina);
        $title = 'Meu histórico de compras';
        
        return view('admin.financeiro.historico-compras', compact('title', 'userCompras'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    public function detalhesCompra(Compras $compra, $id){
        $compra = $compra->where('id', '=', $id)->get()->first();
        
        //redirecionar se o item não existir
        if(!$compra) return redirect()->back();
        
        //usuario logado tem autorização?
        if(!$compra->userAuthorize()) return redirect()->back();
        
        $title = 'Detalhes da compra: '.$compra->referencia;
        $produtos = $compra->getProdutos($id);
        return view('admin.financeiro.detalhes-compra', compact('title', 'compra', 'produtos'));
    }
    
    public function selectMethodPayment(RecargaFormRequest $request) {
        $title = 'Selecione como pagar';
        $data = $request->all();
        
        //adicionar compra em sessão
        session()->put('compra', $data);
        
        return view('admin.financeiro.processo-pagamento.methodPayment', compact('title', 'data'));
    }
}
