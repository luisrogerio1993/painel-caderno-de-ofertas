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
    <div class="text-center div-card-fora" style="display: none;">
        <center>
            <div class="page">
                <div class="page__demo">
                    {!! Form::open(["class" => "form-normal payment-card", "id" => "form-card"]) !!}
                    <div class="payment-card__front">
                        <div class="payment-card__group">
                            <label class="payment-card__field">
                                <span class="payment-card__hint">{{ trans('auth.cardHolder') }}</span>
                                {!! Form::text("cardHolder", "Fulano da Silva", ["class" => "payment-card__input", "placeholder" => trans('auth.cardHolder'), "title" => trans('auth.cardHolder'), "pattern" => "[A-Za-z, ]{2,}", "required" ]) !!}
                            </label>
                        </div>
                        <div class="payment-card__group">
                            <label class="payment-card__field">
                                <span class="payment-card__hint">{{ trans('auth.cardNumber') }}</span>
                                {!! Form::text("cardNumber", "4111111111111111", ["class" => "payment-card__input", "placeholder" => trans('auth.cardNumber'), "title" => trans('auth.cardNumber'), "pattern" => "[0-9]{16}", "required" ]) !!}
                            </label>
                        </div>
                        <div class="payment-card__group">
                            <span class="payment-card__caption">Validade</span>
                        </div>
                        <div class="payment-card__group payment-card__footer">
                            <label class="payment-card__field payment-card__month">
                                <span class="payment-card__hint">{{ trans('auth.cardExpiryMonth') }}</span>
                                {!! Form::text("cardExpiryMonth", 12, ["class" => "payment-card__input", "placeholder" => trans('auth.cardExpiryMonth'), "title" => trans('auth.cardExpiryMonth'), "pattern" => "[0-9]{2}", "required" ]) !!}
                            </label>
                            <span class="payment-card__separator">/</span>
                            <label class="payment-card__field payment-card__year">
                                <span class="payment-card__hint">{{ trans('auth.cardExpiryYear') }}</span>
                                {!! Form::text("cardExpiryYear", 2030, ["class" => "payment-card__input", "placeholder" => trans('auth.cardExpiryYear'), "title" => trans('auth.cardExpiryYear'), "pattern" => "[0-9]{4}", "required" ]) !!}
                            </label>
                        </div>
                    </div>
                    <div class="payment-card__back">
                        <div class="payment-card__group">
                            <label class="payment-card__field payment-card__cvc">
                                <span class="payment-card__hint">{{ trans('auth.cardCVC') }}</span>
                                {!! Form::text("cardCVC", 123, ["class" => "payment-card__input", "placeholder" => trans('auth.cardCVC'), "title" => trans('auth.cardCVC'), "pattern" => "[0-9]{3}", "required" ]) !!}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::hidden("cardName", null) !!}
                    {!! Form::hidden("cardToken", null) !!}
                    {!! Form::submit("Prosseguir", ["class" => "btn btn-danger pull-right btn-buy"]) !!}
                </div>
                {!! Form::close() !!}       
            </div>
        </center>
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
<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
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

$('#form-card').submit(function () {
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
        console.error(data);
        if (data.sucess) {
            location.href = data.payment_link;
        } else {
            alert('Falha.');
        }
    }).fail(function (data) {
        console.log('Error: paymentBillet');
        console.error(data);
    }).always(function () {
        endPreloader();
    });
}

function getBrand() {
    PagSeguroDirectPayment.getBrand({
        cardBin: $('input[name=cardNumber]').val().replace(/ /g, ''),
        success: function (data) {
            console.log("Success GetBrand");
            $('input[name=cardName]').val(data.brand.name);
//            $('.form-cartao-brand').html(data.brand.name);
            createCredCardToken();
        },
        error: function (data) {
            console.log("Error: GetBrand");
            console.log(data);
            endPreloader();
        },
        complete: function () {
        }
    });
}

function createCredCardToken() {
    PagSeguroDirectPayment.createCardToken({
        cardNumber: $('input[name=cardNumber]').val().replace(/ /g, ''),
        brand: $('input[name=cardName]').val(),
        cvv: $('input[name=cardCVC]').val(),
        expirationMonth: $('input[name=cardExpiryMonth]').val(),
        expirationYear: $('input[name=cardExpiryYear]').val(),
        success: function (data) {
            console.log("Success: createCredCardToken");
            $('input[name=cardToken]').val(data.card.token);
            createTransactionCard();
        },
        error: function (data) {
            console.log("Error: createCredCardToken");
            console.log(data);
            endPreloader();
        },
        complete: function () {
        }
    });
}

function createTransactionCard() {
    var senderHash = PagSeguroDirectPayment.getSenderHash();
    var data = $('#form-card').serialize() + "&senderHash=" + senderHash;
    
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
        console.error(data);
        endPreloader();
    }).always(function () {
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
<!-- Folha Css Wizard Breadcrumb <processo pagamento 1 - 2> -->
<link rel="stylesheet" href="{{ url('/assets/css/wizardBreadcrumb.css') }} ">
<link rel="stylesheet" href="{{ url('/assets/css/creditcard.css') }} ">
<!--Script de fade-->
<script src="{{ url('/assets/js/fade.js') }}"></script>
@endpush