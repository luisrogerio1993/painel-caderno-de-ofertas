<?php

namespace App\Http\Controllers\Admin\Pagamentos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pagamentos\Pagseguro;
use App\Models\Compras;
use App\Models\Produtos_loja;

class PagseguroController extends Controller
{
    
    public function getCode(Pagseguro $pagseguro) {
        return $pagseguro->getSessionId();
    }
    
    public function billetTransaction(Request $request, Pagseguro $pagseguro, Compras $compra) {
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
        
        return response()->json($response, 200); //$response
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
}
