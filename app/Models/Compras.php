<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Lista_produtos_compra;
use App\User;
use Auth;

class Compras extends Model
{
    protected $table = 'compras';
    protected $fillable = ['user_id', 'referencia', 'codigo_transacao', 'status', 'metodo_pagamento'];


    public function getProdutos($id) {
        return Lista_produtos_compra::where('compra_id', '=', $id)->get();
    }
    
    public function novaCompra($item, $referencia, $codigoTransacao, $status = 1, $metodoPagamento = 2) {
        $compra = $this->create([
            'user_id' => auth()->user()->id,
            'referencia' => $referencia,
            'codigo_transacao' => $codigoTransacao,
            'status' => $status,
            'metodo_pagamento' => $metodoPagamento,
        ]);
        //$item -> id, desc, valor
        $listaItensCompra = [
            $item->id => [
                'valor_unitario' => $item->valor,
                'quantidade' => $item->quantidade,
            ],
        ];
        
        //add to lista_produtos_compras
        //criar um laço de repetição se preciso depois
        Lista_produtos_compra::create([
            'compra_id' => $compra->id,
            'produto_id' => $item->id,
            'valor_unitario' => $item->valor,
            'quantidade' => $item->quantidade,
        ]);
        
    }
    
    public function getStatus() {
        $status = [
            1 => 'Aguardando pagamento',
            2 => 'Em análise',
            3 => 'Paga',
            4 => 'Disponível',
            5 => 'Em disputa',
            6 => 'Devolvida',
            7 => 'Cancelada',
            8 => 'Debitado',
            9 => 'Retenção temporária',
        ];
        
        return $status[$this->status];
    }
    
    public function getMetodoPagamento() {
        $metodoPagamento = [
            1 => 'Cartão de crédito',
            2 => 'Boleto',
            3 => 'Débito online (TEF)',
            4 => 'Saldo PagSeguro',
            5 => 'Oi Paggo',
            7 => 'Depósito em conta',
        ];
        
        return $metodoPagamento[$this->metodo_pagamento];
    }
    
    public function getDateCreate() {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }
    
    public function getValorTotal($id) {
        $produtosDessaCompra = Lista_produtos_compra::where('compra_id', '=', $id)->get();
        $valorTotal = 0;
        foreach ($produtosDessaCompra as $produto) {
            $valorTotal += $produto->valor_unitario * $produto->quantidade;
        }
        return $valorTotal;
    }
    
    public function updateStatus($status) {
        $this->status = $status;
        $this->save();
    }
    
    public function sePagamentoAprovado($response) {
        //enviar email ao comprador
        $user = User::where('email', '=', $response['emailComprador'])->get()->first();
        $userInArray = User::where('email', '=', $response['emailComprador'])->get()->first()->toArray();
        $userInArray += ['referencia' => $response['reference']];
        
        $user->notifyAprovedOrder($userInArray);
        
        if($response['produtoId'] == 1){
            //Compra de creditos
            //adicionar creditos ao comprador
            $user->addCreditos($response['produtoQuantity']);
        }
    }
    
    public function userAuthorize() {
        if(Auth::user()->is_admin) return true;
        return $this->user_id == auth()->user()->id;
    }
}
