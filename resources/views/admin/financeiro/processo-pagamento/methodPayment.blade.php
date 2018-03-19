@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
@stop

@section('content')

<ul class="nav nav-wizard" style="margin-bottom: 50px">
    <li class="active"><a href="#"><span class="badge badge-step">1</span> Escolha como pagar</a></li>
    <li><a href="#"><span class="badge badge-step">2</span> Conclua o pagamento</a></li>
</ul>
<div id="conteudo-pagamento">
    {!! Form::open(["class" => "form-normal", "id" => "form-valor-recarga"]) !!}
    <div class="row">
        <div class="col-xs-1 text-right text-success" style="padding: 9px;">R$ </div>
        <div class="col-xs-11">   
            {!! Form::text("quantidade_item", null, ["class" => "form-control", "placeholder" => trans('auth.quantidade_item'), "title" => trans('auth.quantidade_item'), "disabled", "required" ]) !!}
            {!! Form::close() !!}
        </div>
    </div>

    <ul class="list-group menuPagamento">
        <a href="#" id="payment-billet">
            <li class="list-group-item">
                <span class="glyphicon glyphicon-barcode "></span> Boleto
            </li>
        </a>
        <a href="#" id="show-card">
            <li class="list-group-item">
                <span class="glyphicon glyphicon-credit-card"></span> Cartão de crédito
            </li>     
        </a>
    </ul>
    <div class="col-xs-6 col-xs-offset-3 div-card-fora" style="display: none;">
        <div> 
            <h1 class="form-cartao-brand">CARD</h1>
        </div>
        {!! Form::open(["class" => "form-normal", "id" => "form-card"]) !!}
            <div class="form-group">              
                {!! Form::text("cardNumber", "4111111111111111", ["class" => "form-control", "placeholder" => trans('auth.cardNumber'), "title" => trans('auth.cardNumber'), "required" ]) !!}
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        {!! Form::text("cardExpiryMonth", 12, ["class" => "form-control", "placeholder" => trans('auth.cardExpiryMonth'), "title" => trans('auth.cardExpiryMonth'), "required" ]) !!}
                    </div>                                  
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        {!! Form::text("cardExpiryYear", 2030, ["class" => "form-control", "placeholder" => trans('auth.cardExpiryYear'), "title" => trans('auth.cardExpiryYear'), "required" ]) !!}
                    </div>                                  
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        {!! Form::text("cardCVC", 123, ["class" => "form-control", "placeholder" => trans('auth.cardCVC'), "title" => trans('auth.cardCVC'), "required" ]) !!}
                    </div>                                  
                </div>
            </div>
            <div class="form-group">
                {!! Form::hidden("cardName", null) !!}
                {!! Form::hidden("cardToken", null) !!}
                {!! Form::submit("Prosseguir", ["class" => "btn btn-primary pull-right btn-buy"]) !!}
            </div>
        {!! Form::close() !!}       
    </div>
</div>
<div class="row text-center" id="conteudo-preloader" style="display: none;">
    <div class="row">
        <center>
        <span class="info-box-icon-override" title="Aguarde...">
            <i class="fa fa-refresh fa-spin"></i>
        </span>
        </center>
    </div>
    <div class="row">
        <blockquote data-pg-collapsed> 
            <h2 class="text-danger">Aguarde...</h2> 
        </blockquote>
    </div>
</div>
@stop

@push('scriptsCadernoPage')
        <!--jQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!--Checkout transparent-->
        <script src="{{ config('pagseguro.url_transparent_js') }}"></script>

<script>
$(function () {
    startPreloader();
    setSessionId();
});

$(function () {
    $('#show-card').click(function () {
        $(".div-card-fora").slideToggle();
        return false;
    });
});

$(function () {
    $('#payment-billet').click(function () {
        startPreloader();
        paymentBillet();
        return false;
    });
});

$('#form-card').submit(function(){
    startPreloader();
    getBrand();
    return false;
});

function setSessionId() {
    var data = $('#form-valor-recarga').serialize();
    $.ajax({
        url: "{{ route('admin.pagseguro.transparent.code') }}",
        method: "POST",
        data: data
    }).done(function (data) {
        console.log("Success: setSessionId");
        PagSeguroDirectPayment.setSessionId(data);
    }).fail(function (data) {
        console.log('Error: setSessionIdCard');
        console.log(data);
    }).always(function () {
        endPreloader();
    });
}

function paymentBillet() {
    var sendHash = PagSeguroDirectPayment.getSenderHash();
    var data = $('#form-valor-recarga').serialize() + "&sendHash=" + sendHash;
    $.ajax({
        url: "{{ route('admin.pagseguro.transparent.billet') }}",
        method: "POST",
        data: data
    }).done(function (data) {
        console.log("Success: paymentBillet");
        if (data.sucess) {
            location.href = data.payment_link;
        } else {
            alert('Falha.');
        }
    }).fail(function () {
        console.log('Error: paymentBillet');
        console.log(data);
    }).always(function () {
        endPreloader();
    });
}

function getBrand(){
    PagSeguroDirectPayment.getBrand({
        cardBin: $('input[name=cardNumber]').val().replace(/ /g, ''),
        success: function (data){
            console.log("Success GetBrand");
            $('input[name=cardName]').val(data.brand.name);
//            $('.form-cartao-brand').html(data.brand.name);
            createCredCardToken();
        },
        error: function(data) {
            console.log("Error: GetBrand");
            console.log(data);
            endPreloader();
        },
        complete: function(){
        }
    });
}

function createCredCardToken(){
    PagSeguroDirectPayment.createCardToken({
        cardNumber: $('input[name=cardNumber]').val().replace(/ /g, ''),
        brand: $('input[name=cardName]').val(),
        cvv: $('input[name=cardCVC]').val(),
        expirationMonth: $('input[name=cardExpiryMonth]').val(),
        expirationYear: $('input[name=cardExpiryYear]').val(),
        success: function (data){
            console.log("Success: createCredCardToken");
            $('input[name=cardToken]').val(data.card.token);
            createTransactionCard();
        },
        error: function(data) {
            console.log("Error: createCredCardToken");
            console.log(data);
            endPreloader();
        },
        complete: function(){
        }
    });
}

function createTransactionCard(){
    var senderHash = PagSeguroDirectPayment.getSenderHash();
    var data = $('#form-card').serialize()+"&senderHash="+senderHash;;
    $.ajax({
        url: "{{ route('admin.pagseguro.transparent.cardTransaction') }}",
        method: "POST",
        data: data
    }).done(function (data) {
        console.log("Success: createTransactionCard");
        endPreloader();
        if (data.sucess) {
            location.href = "{{ route ('admin.financeiro.historico') }}";
        } else {
            alert('Falha.');
        }
    }).fail(function (data) {
        console.log("Error: createTransactionCard");
        console.log(data);
        endPreloader();
    }).always(function(){
    });
}
            
function startPreloader() {
    $("#conteudo-pagamento").slideToggle();
    $("#conteudo-preloader").slideToggle();
    $('a').css({"pointer-events": "none"});
}

function endPreloader() {
    $("#conteudo-pagamento").slideToggle();
    $("#conteudo-preloader").slideToggle();
    $('a').css({"pointer-events": ""});
}
</script>
@endpush
@push('cssCadernoPage')
<!-- Folha Css Wizard Breadcrumb-->
<link rel="stylesheet" href="{{ url('/assets/css/wizardBreadcrumb.css') }} ">
<!--Script de fade-->
<script src="{{ url('/assets/js/fade.js') }}"></script>
@endpush