@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.financeiro'), 'fa-ion ion-cash') !!}
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Meu Saldo</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!--conteudo-->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>R$ {{ number_format(Auth::user()->credito_disponivel, 2, '.', '') }}</h3>
                        <p>Disponíveis em minha conta</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="{{ route('admin.financeiro.historico') }}" class="small-box-footer">Ver meu histórico de recargas <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@if ($errors->has('quantidade_item'))
<div class="alert alert-danger alert-messageReturn">
    <strong>{{ $errors->first('quantidade_item') }}</strong>
</div>
@endif
@if ($errors->has('id_item'))
<div class="alert alert-danger alert-messageReturn">
    <strong>{{ $errors->first('id_item') }}</strong>
</div>
@endif
    <div class="box box-success collapsed-box">
        <div class="box-header with-border" data-widget="collapse" style="cursor: pointer">
            <h3 class="box-title">Recarregar</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body" style="display: none;">
            {!! Form::open(["route" => "admin.financeiro.selectMethodPayment", "class" => "form-horisontal"]) !!}
            <div class="row" data-pg-id="96">
                <div class="col-xs-1 text-right text-success" style="padding: 9px;">R$ </div>
                <div class="col-xs-11">   
                    {!! Form::text("quantidade_item", null, ["class" => "form-control", "placeholder" => trans('auth.quantidade_item'), "title" => trans('auth.quantidade_item') ]) !!}
                    {!! Form::hidden("id_item", 1) !!}
                </div>
            </div>
            <br/>
                {!! Form::submit("Prosseguir", ["class" => "btn btn-success btn-block", "name" => "enviar"]) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop