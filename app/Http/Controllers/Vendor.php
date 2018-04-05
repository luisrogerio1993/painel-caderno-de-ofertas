<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use DateTime;
use App\Models\Estabelecimentos;
use App\Models\Anuncios;
use App\Models\Lista_ufs;
use Cep;

class Vendor extends Controller {
    /*
     * FUNÇÕES
     */
    public static function randomFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    public static function addMenuLocalizacaoPainel($arrayRouteTitle, $glyphicon) {

        $arrayValorPorIndice = array_keys($arrayRouteTitle);
        $retornoInicio = "<p class=\"pull-left\">"
                        . '<h5>';
        $retornoMeio = "";
        $retornoFim = "</h5>"
                        . "<p>"
                        . "<h4 class=\"text-center\"><span class=\"". $glyphicon ."\" style=\"padding-right: 15px;\"></span>".$arrayValorPorIndice[count($arrayValorPorIndice)-1]."</h4>"
                        . "</p>";


        foreach ($arrayRouteTitle as $title => $route) {
            $retornoMeio .= "<a href=\"" . route($route) . "\" class=\"btn btn-default\">" . $title . "</a>";
            if ($title !== $arrayValorPorIndice[count($arrayValorPorIndice)-1]) {
                $retornoMeio .= "<span class=\"glyphicon glyphicon-menu-right\"></span>";
            }
        }
        
        return $retornoInicio . $retornoMeio . $retornoFim;
    }
    
    public static function apagarArquivo($destino, $nomeImagem){
        if (!file_exists($destino)) return TRUE;
        if (!is_dir($destino)) return unlink($destino);
        
        foreach (scandir($destino) as $item) {
            if (($item == '.') or ($item == '..')) continue;
            if (strstr($destino . DIRECTORY_SEPARATOR . $item, $nomeImagem)) {
                unlink($destino . DIRECTORY_SEPARATOR . $item);
                return true;
            }
        }
        return false;
    }

    public static function salvarImagemUpload($requestGet, $destinoGet){
        //config
        $image = is_uploaded_file($requestGet) ? $requestGet : $requestGet->file('image');
        $destino = public_path($destinoGet);
        $imageNome = DateTime::createFromFormat('U.u', microtime(true))->format("H_i_s_u__d_m_Y");
            $imageNome .= '.'.$image->getClientOriginalExtension();
        $larguraAlturaMaxima = 1200; //px
        $larguraAlturaMinima = 150; //px
        
        if (Image::make($image->getRealPath())->height()
                < $larguraAlturaMinima || 
            Image::make($image->getRealPath())->width()
                < $larguraAlturaMinima){
            return ['status' => false, 'return' => 'Imagem muito pequena. Tamanho minimo: '.$larguraAlturaMinima.'px'];
        }

        if (Image::make($image->getRealPath())->height()
                > $larguraAlturaMaxima || 
            Image::make($image->getRealPath())->width()
                > $larguraAlturaMaxima){
            
            //redimensionar se necessário
            $imageAlterada = Image::make($image->getRealPath())->resize($larguraAlturaMaxima, $larguraAlturaMaxima, function ($constraint) {
                $constraint->aspectRatio();
            });
            
            //salvar
            return $imageAlterada->save($destino.'/'.$imageNome, 100) ? ['status' => true, 'return' => $imageNome] : ['status' => false, 'return' => 'Erro ao salvar imagem.'];
        }else{
            //salvar
            return $image->move($destino, $imageNome) ? ['status' => true, 'return' => $imageNome] : ['status' => false, 'return' => 'Erro ao salvar imagem.'];
        }
    }
    
    public static function getTotalAnunciosUsuario($idUsuario) {
        $total = 0;
        //pegar todos os estabelecimentos do usuario atual
        //checar em cada estabelecimento quantos anuncios tem
        foreach (Estabelecimentos::where('user_id', '=', $idUsuario)->get() as $estabelecimento) {
            $total += Anuncios::where('estabelecimento_id', '=', $estabelecimento->id)->count();
        }
        return $total;
    }
    
    public static function addModalView($titulo, $conteudo, $tipoModal, $id = 'modal-info', $botaoCancelar = null, $idFormEnviar = null) {
        
        $modalInicio = 
'<div class="modal modal-'.$tipoModal.' fade" id="'.$id.'">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">'.$titulo.'</h4>
            </div>
            <div class="modal-body">
                <p>'.$conteudo.'</p>
            </div>
            <div class="modal-footer">';
        $modalMeio = '';
        if($botaoCancelar != null){
            $modalMeio .= '<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">'.$botaoCancelar.'</button>';
        }
        if($idFormEnviar != null){
            $modalMeio .= '<button type="button" class="btn btn-outline pull-right" onclick="document.getElementById(\''.$idFormEnviar.'\').submit()" >Presseguir</button>';
        }
        $modalFim = '</div>
        </div>
    </div>
</div>';
        
        return $modalInicio.$modalMeio.$modalFim;
    }
    
    public static function tirarAcentos($array){
        $arrayReturn = [];
        foreach ($array as $key => $string) {
            $arrayReturn[$key] = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
        }
        return $arrayReturn;
    }
    
    public static function searchByNome(Request $request, $model, $colunaBdBusca)
    {
        $data = $request->all();
        if(!$request->has('key-search')){
            return 'required_key-search';
        }
        
        $result = $model->where($colunaBdBusca, 'LIKE', "%{$data['key-search']}%")
                ->paginate(config('constantes.ITENS_POR_PAGINA'));
        
        return count($result) > 0 ? $result : 'Nenhum encontrado';
    }
    
    public static function buscarCEP($cep){
        //buscar CEP
        if ( $resultCEPGet = Cep::find($cep)->toObject()->result() ){
            //pegar ID da UF
            if ( $UfID = Lista_ufs::where('nome', $resultCEPGet->uf)->first() ){
                return ['ufID' => $UfID->id, 'localidade' => $resultCEPGet->localidade];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public static function avisoNavegadorCompativel() {
        $listaNavegadores = ['MSIE', 'Firefox', 'Chrome', 'Safari'];
        $navegadorAtual = $_SERVER['HTTP_USER_AGENT'];
        foreach($listaNavegadores as $navegador){
            if(strrpos($navegadorAtual, $navegador)){
                $navegadorAtual = $navegador;
            }
        }
        if(strrpos($navegadorAtual, 'Firefox') != 0){
            return 'Seu navegador atual é o '. $navegadorAtual .', para total compactibilidade com a plataforma recomendamos o Mozilla Firefox.';
        }
    }
    
    public static function adicionarImagemLink($objeto, $constanteDestino) {
        if(!is_array($objeto)){
            if ($objeto->image != null){
                $objeto->image = env('APP_URL').'/'.$constanteDestino.'/'.$objeto->image;
            }
        }else{
            if ($objeto['image'] != null){
                $objeto['image'] = env('APP_URL').'/'.$constanteDestino.'/'.$objeto['image'];
            }
        }
        return $objeto;
    }
}

//não usada
//    public static function restaurarMoedaParaDB($valor) {
//        $valor = str_replace('.', '', $valor);
//        return str_replace(',', '.', $valor);
//    }