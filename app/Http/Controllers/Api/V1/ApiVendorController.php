<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiVendorController extends Controller
{

    public static function searchByNome(Request $request, $model, $colunaBdBusca)
    {
        $data = $request->all();
        if(!$request->has('key-search')){
            return response()->json(['error' => 'required_key-search'], 422);
        }
        
        $result = $model->where($colunaBdBusca, 'LIKE', "%{$data['key-search']}%")
                ->paginate(config('constantes.ITENS_POR_PAGINA'));
        
        return count($result) > 0 ? response()->json(['data' => $result]) : response()->json(['error' => 'not_found'], 404);
    }
    
    public static function searchByCategory(Request $request, $model, $colunaBdBusca)
    {
        $data = $request->all();
        if(!$request->has('key-search')){
            return response()->json(['error' => 'required_key-search'], 422);
        }else if(!is_numeric($data['key-search'])){
            return response()->json(['error' => 'invalid_id_category'], 422);
        }
        
        $result = $model->where($colunaBdBusca, $data['key-search'])
                ->paginate(config('constantes.ITENS_POR_PAGINA'));
        
        return count($result) > 0 ? response()->json(['data' => $result]) : response()->json(['error' => 'not_found'], 404);
    }
}
