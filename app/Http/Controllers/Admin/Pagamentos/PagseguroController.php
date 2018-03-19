<?php

namespace App\Http\Controllers\Admin\Pagamentos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pagamentos\Pagseguro;
use App\Models\Compras;
use App\Models\Produtos_loja;

class PagseguroController extends Controller
{
    public function pagseguro(Pagseguro $pagseguro) {
        $code = $pagseguro->generate();
        $urlRedirect = config('pagseguro.url_redirect_after_request').$code;
        
        return redirect()->away($urlRedirect);
    }
    
    public function transparent() {
        return view('admin.pagamentos.pagseguro.index');
    }
    
    public function getCode(Pagseguro $pagseguro) {
        return $pagseguro->getSessionId();
    }
    
    public function billet(Request $request, Pagseguro $pagseguro, Compras $compra) {
        //verificar se foi criada a stancia de COMPRA -> Session
        if(!session()->has('compra')){
            redirect()->route('admin.financeiro')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao realizar compra.',]]);
        }
        
        $response = $pagseguro->PaymentBillet($request->sendHash);
        
        //criar nova compra para o usuario
        $itemDaCompraSession = session()->get('compra');
        $dadosDoItem = Produtos_loja::find($itemDaCompraSession['id_item'])->get();
        $dadosDoItem[0]['quantidade'] = $itemDaCompraSession['quantidade_item'];
        $compra->novaCompra($dadosDoItem[0], $response['reference'], $response['code'], 1, 2);
        
        //limpar compra porque foi feita com sucesso
        session()->forget('compra');
        
        return response()->json($response, 200);
    }
    
    
    public function cardTransaction(Request $request, Pagseguro $pagseguro, Compras $compra) {
        //verificar se foi criada a stancia de COMPRA -> Session
        if(!session()->has('compra')){
            redirect()->route('admin.financeiro')->with('messageReturn', ['status' => false, 'messages' => ['Falha ao realizar compra.',]]);
        }
        
        $response = $pagseguro->paymentCredCard($request);
        
        //criar nova compra para o usuario
        $itemDaCompraSession = session()->get('compra');
        $dadosDoItem = Produtos_loja::find($itemDaCompraSession['id_item'])->get();
        $dadosDoItem[0]['quantidade'] = $itemDaCompraSession['quantidade_item'];
        $compra->novaCompra($dadosDoItem[0], $response['reference'], $response['code'], 1, 1);
        
        //limpar compra porque foi feita com sucesso
        session()->forget('compra');
        
        return response()->json($response, 200);
    }
                                public function card() {
                                    return view('admin.pagamentos.pagseguro.card');
                                }
    
}
