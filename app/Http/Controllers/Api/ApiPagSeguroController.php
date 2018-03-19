<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pagamentos\Pagseguro;
use App\Models\Compras;

class ApiPagSeguroController extends Controller
{
    public function request(Request $request, Pagseguro $pagseguro, Compras $compras) {
        if(!$request->notificationCode){
            return response()->json(['error' => 'NotNotificationCode'], 404);
        }
        
        $response = $pagseguro->getStatusTransaction($request->notificationCode);
        
        
        $compra = $compras->where('referencia', '=', $response['reference'])->get()->first();
        $compra->updateStatus($response['status']);
        
        if($response['status'] == 3){ //pagamento aprovado
            $compra->sePagamentoAprovado($response);
        }
        
        return response()->json(['success' => true], 200);
    }
}

/*
 * https://hookb.in/Zm88yaYn
 * Postman
 */