<?php

namespace App\Models\Pagamentos;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as Guzzle;
use App\Models\Produtos_loja;
use Auth;
use App\Models\Estabelecimentos;
use App\Http\Controllers\Vendor;

class Pagseguro extends Model
{
    private $reference;
    private $user;
    private $currency = 'BRL';
    private $country = 'Brasil';
    private $estabelecimentoParaDados;
    
    //baseado em compra 1 item
    private $dadosDaCompra;
    private $dadosDoItem;


    public function __construct() {
        if(auth()->check()){
            $this->reference = uniqid(date("YmdHis"));
            $this->user = auth()->user();
            $this->estabelecimentoParaDados = Estabelecimentos::where('user_id', '=', $this->user->id)->first();

            //baseado em compra 1 item
            $this->dadosDaCompra = session()->get('compra');
            $this->dadosDoItem = Produtos_loja::find($this->dadosDaCompra['id_item']);
        }
    }
    
    public function getSessionId() {
        $params = $this->getConfigs();
        $params = http_build_query($params);
        
        $guzzle = new Guzzle;
        $response = $guzzle->request('POST', config('pagseguro.url_transparent_session'), [
            'query' => $params,
        ]);
        $body = $response->getBody();
        $content = $body->getContents();
        $xml = simplexml_load_string($content); 
        
        return $xml->id;
    }
    
    public function PaymentBillet($request) {
        $params = [
            'senderHash' => $request, 
            'paymentMode' => 'default', 
            'paymentMethod' => 'boleto', 
            'currency' => $this->currency, 
            'reference' => $this->reference,
        ];
        //$params = http_build_query($params);
        $params = array_merge($params, $this->getConfigs());
        $params = array_merge($params, $this->getItens());
        $params = array_merge($params, $this->getSender());
        $params = array_merge($params, $this->getShipping());
        
        $params = Vendor::tirarAcentos($params);
        
        $guzzle = new Guzzle;
        $response = $guzzle->request('POST', config('pagseguro.url_payment_transparent'), [
            'form_params' => $params,
        ]);
        $body = $response->getBody();
        $content = $body->getContents();
        
        $xml = simplexml_load_string($content);
        
        return [
            'sucess'        => true,
            'payment_link'  => (string)$xml->paymentLink,
            'reference'     => $this->reference,
            'code'          => (string)$xml->code,
        ];
    }
    
    public function paymentCredCard($request) {
        $params = [
            'paymentMode' => 'default',
            'paymentMethod' => 'creditCard',
            'receiverEmail' => config('constantes.EMAIL_CADERNO_DE_OFERTAS'),
            'currency' => $this->currency, 
            'reference' => $this->reference,
            'senderHash' => $request->senderHash, 
            'creditCardToken' => $request->cardToken,
            
            'installmentQuantity' => '1',
            'installmentValue' => number_format($this->dadosDoItem->valor * $this->dadosDaCompra['quantidade_item'], 2, '.', ''),
//            'noInterestInstallmentQuantity' => '1',
            
        ];
        
        $params = array_merge($params, $this->getConfigs());
        $params = array_merge($params, $this->getItens());
        $params = array_merge($params, $this->getSender());
        $params = array_merge($params, $this->getShipping());
        $params = array_merge($params, $this->getBilling());
        $params = array_merge($params, $this->getCreditCardHolder());
        
        $params = Vendor::tirarAcentos($params);
        
        //$params = http_build_query($params);
        
        $guzzle = new Guzzle;
        $response = $guzzle->request('POST', config('pagseguro.url_payment_transparent'), [
            'form_params' => $params,
        ]);
        
        $body = $response->getBody();
        $content = $body->getContents();
        
        $xml = simplexml_load_string($content);
        
        return [
            'sucess'        => true,
            'reference'     => $this->reference,
            'code'          => (string)$xml->code,
        ];
    }
    
    public function getConfigs() {
        return [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
            'notificationURL' => config('pagseguro.url_notification_cdo'),
        ];
    }
    
    public function getItens() {
        return [
            'itemId1' => $this->dadosDoItem->id, 
            'itemDescription1' => $this->dadosDoItem->descricao,
            'itemAmount1' => number_format($this->dadosDoItem->valor, 2, '.', ''),
            'itemQuantity1' => $this->dadosDaCompra['quantidade_item'],
            
            'extraAmount' => number_format(0, 2, '.', ''),
        ];
    }
    
    function getSender() {
        $CPFouCNPJ = $this->estabelecimentoParaDados->cpf != null ? $this->estabelecimentoParaDados->cpf : $this->estabelecimentoParaDados->cnpj;
        $DDD = $this->estabelecimentoParaDados->telefone_fixo != null ? $this->estabelecimentoParaDados->ddd_telefone_fixo : $this->estabelecimentoParaDados->ddd_telefone_celular;
        $telefone = $this->estabelecimentoParaDados->telefone_fixo != null ? $this->estabelecimentoParaDados->telefone_fixo : $this->estabelecimentoParaDados->telefone_celular;
        return [
            'senderName' => $this->user->name,
            'senderCPF' => $CPFouCNPJ,
            'senderAreaCode' => $DDD,
            'senderPhone' => $telefone,
            'senderEmail' => $this->user->email,
        ];
    }
    
    function getShipping() {
        return [
            'shippingType' => '1', 
            'shippingCost' => number_format(0, 2, '.', ''),
            'shippingAddressStreet' => $this->estabelecimentoParaDados->rua,
            'shippingAddressNumber' => $this->estabelecimentoParaDados->numero, 
            'shippingAddressComplement' => $this->estabelecimentoParaDados->complemento,
            'shippingAddressDistrict' => $this->estabelecimentoParaDados->bairro,
            'shippingAddressPostalCode' => $this->user->cep, 
            'shippingAddressCity' => $this->user->cidade,
            'shippingAddressState' => $this->user->getEstado()->nome,
            'shippingAddressCountry' => $this->country,  
        ];
    }
    
    function getBilling() {
        return [
            'billingAddressStreet' => $this->estabelecimentoParaDados->rua,
            'billingAddressNumber' => $this->estabelecimentoParaDados->numero, 
            'billingAddressComplement' => $this->estabelecimentoParaDados->complemento,
            'billingAddressDistrict' => $this->estabelecimentoParaDados->bairro,
            'billingAddressPostalCode' => $this->user->cep, 
            'billingAddressCity' => $this->user->cidade,
            'billingAddressState' => $this->user->getEstado()->nome,
            'billingAddressCountry' => $this->country,  
        ];
    }
    
    function getCreditCardHolder() {
        $CPFouCNPJ = $this->estabelecimentoParaDados->cpf != null ? $this->estabelecimentoParaDados->cpf : $this->estabelecimentoParaDados->cnpj;
        $DDD = $this->estabelecimentoParaDados->telefone_fixo != null ? $this->estabelecimentoParaDados->ddd_telefone_fixo : $this->estabelecimentoParaDados->ddd_telefone_celular;
        $telefone = $this->estabelecimentoParaDados->telefone_fixo != null ? $this->estabelecimentoParaDados->telefone_fixo : $this->estabelecimentoParaDados->telefone_celular;
        
        return [
            'creditCardHolderName' => $this->user->name,
            'creditCardHolderCPF' => $CPFouCNPJ,
            'creditCardHolderBirthDate' => '01/01/1900',
            'creditCardHolderAreaCode' => $DDD,
            'creditCardHolderPhone' => $telefone,
        ];
    }
    
    function getStatusTransaction($notificationCode) {
        $configs = $this->getConfigs();
        $params = http_build_query($configs);
        
        $guzzle = new Guzzle;
        $response = $guzzle->request('GET', config('pagseguro.url_notification')."/".$notificationCode, [
            'query' => $params,
        ]);
        $body = $response->getBody();
        $content = $body->getContents();
        
        $xml = simplexml_load_string($content);
        
        return [
            'status'            => (string) $xml->status,
            'reference'         => (string) $xml->reference,
            'emailComprador'    => (string) $xml->sender->email,
            'produtoId'         => (string) $xml->items->item->id,
            'produtoQuantity'   => (string) $xml->items->item->quantity,
        ];
    }
}
