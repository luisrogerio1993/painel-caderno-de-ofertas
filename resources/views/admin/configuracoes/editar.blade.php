@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.config.editar'), 'glyphicon glyphicon-cog') !!}
@stop

@section('content')
{!! Form::model($configurações, ["route" => "admin.config.editar", "class" => "form-normal", "method" => "put"]) !!}
<div class="form-group has-feedback {{ $errors->has('valor_anuncio_padrao') ? 'has-error' : '' }}">
    {!! Form::label("valor_anuncio_padrao", trans('auth.valor_anuncio_padrao'), ['class' => 'label-form']) !!}
    {!! Form::text("valor_anuncio_padrao", $configurações->valor_anuncio_padrao, ["class" => "form-control", "placeholder" => trans('auth.valor_anuncio_padrao'), "onkeypress" => "return(mascaraMoeda(this,'','.',event))", "title" => trans('auth.valor_anuncio_padrao'), "required" ]) !!}
    <span class="glyphicon glyphicon-usd form-control-feedback"></span>
    @if ($errors->has('valor_anuncio_padrao'))
    <span class="help-block">
        <strong>{{ $errors->first('valor_anuncio_padrao') }}</strong>
    </span>
    @endif
</div>
<div class="form-group has-feedback {{ $errors->has('valor_anuncio_premium') ? 'has-error' : '' }}">
    {!! Form::label("valor_anuncio_premium", trans('auth.valor_anuncio_premium'), ['class' => 'label-form']) !!}
    {!! Form::text("valor_anuncio_premium", null, ["class" => "form-control", "placeholder" => trans('auth.valor_anuncio_premium'), "onkeypress" => "return(mascaraMoeda(this,'','.',event))", "title" => trans('auth.valor_anuncio_premium'), "required" ]) !!}
    <span class="glyphicon glyphicon-usd form-control-feedback"></span>
    @if ($errors->has('valor_anuncio_premium'))
    <span class="help-block">
        <strong>{{ $errors->first('valor_anuncio_premium') }}</strong>
    </span>
    @endif
</div>
<div class="form-group has-feedback {{ $errors->has('valor_anuncio_proximidade_fisica') ? 'has-error' : '' }}">
    {!! Form::label("valor_anuncio_proximidade_fisica", trans('auth.valor_anuncio_proximidade_fisica'), ['class' => 'label-form']) !!}
    {!! Form::text("valor_anuncio_proximidade_fisica", null, ["class" => "form-control", "placeholder" => trans('auth.valor_anuncio_proximidade_fisica'), "onkeypress" => "return(mascaraMoeda(this,'','.',event))", "title" => trans('auth.valor_anuncio_proximidade_fisica'), "required" ]) !!}
    <span class="glyphicon glyphicon-usd form-control-feedback"></span>
    @if ($errors->has('valor_anuncio_proximidade_fisica'))
    <span class="help-block">
        <strong>{{ $errors->first('valor_anuncio_proximidade_fisica') }}</strong>
    </span>
    @endif
</div>
<div class="form-group has-feedback {{ $errors->has('limite_anuncios_anunciante') ? 'has-error' : '' }}">
    {!! Form::label("limite_anuncios_anunciante", trans('auth.limite_anuncios_anunciante'), ['class' => 'label-form']) !!}
    {!! Form::text("limite_anuncios_anunciante", null, ["class" => "form-control", "placeholder" => trans('auth.limite_anuncios_anunciante'), "title" => trans('auth.limite_anuncios_anunciante'), "required" ]) !!}
    <span class="glyphicon glyphicon-sort form-control-feedback"></span>
    @if ($errors->has('limite_anuncios_anunciante'))
    <span class="help-block">
        <strong>{{ $errors->first('limite_anuncios_anunciante') }}</strong>
    </span>
    @endif
</div>
<div class="form-group has-feedback {{ $errors->has('limite_estabelecimentos_anunciante') ? 'has-error' : '' }}">
    {!! Form::label("limite_estabelecimentos_anunciante", trans('auth.limite_estabelecimentos_anunciante'), ['class' => 'label-form']) !!}
    {!! Form::text("limite_estabelecimentos_anunciante", null, ["class" => "form-control", "placeholder" => trans('auth.limite_estabelecimentos_anunciante'), "title" => trans('auth.limite_estabelecimentos_anunciante'), "required" ]) !!}
    <span class="glyphicon glyphicon-sort form-control-feedback"></span>
    @if ($errors->has('limite_estabelecimentos_anunciante'))
    <span class="help-block">
        <strong>{{ $errors->first('limite_estabelecimentos_anunciante') }}</strong>
    </span>
    @endif
</div>
<div class="form-group has-feedback {{ $errors->has('texto_aviso') ? 'has-error' : '' }}">
    {!! Form::label("texto_aviso", trans('auth.texto_aviso'), ['class' => 'label-form']) !!}
    {!! Form::textarea("texto_aviso", null, ["class" => "form-control", "placeholder" => trans('auth.texto_aviso'), "rows" => "2", "title" => trans('auth.texto_aviso'), "title" => trans('auth.texto_aviso') ]) !!}
    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
    @if ($errors->has('texto_aviso'))
    <span class="help-block">
        <strong>{{ $errors->first('texto_aviso') }}</strong>
    </span>
    @endif
</div>
<div class="form-group has-feedback {{ $errors->has('mostrar_aviso_para') ? 'has-error' : '' }}">
    {!! Form::label("mostrar_aviso_para", trans('auth.mostrar_aviso_para'), ['class' => 'label-form']) !!}
    {!! Form::select("mostrar_aviso_para", $configuraçõesMostrarAvisoPara, null, ["class" => "form-control", "placeholder" => trans('auth.mostrar_aviso_para'), "title" => trans('auth.mostrar_aviso_para') ]) !!}

    @if ($errors->has('mostrar_aviso_para'))
    <span class="help-block">
        <strong>{{ $errors->first('mostrar_aviso_para') }}</strong>
    </span>
    @endif
</div>
<div class="form-group has-feedback {{ $errors->has('configuracao_aviso') ? 'has-error' : '' }}">
    {!! Form::label("configuracao_aviso", trans('auth.configuracao_aviso'), ['class' => 'label-form']) !!}
    {!! Form::select("configuracao_aviso", $configuraçõesConfiguracaoAviso, null, ["class" => "form-control", "placeholder" => trans('auth.configuracao_aviso'), "title" => trans('auth.configuracao_aviso') ]) !!}
    @if ($errors->has('configuracao_aviso'))
    <span class="help-block">
        <strong>{{ $errors->first('configuracao_aviso') }}</strong>
    </span>
    @endif
</div>

<div class="form-group">
    {!! Form::submit("Alterar", ["class" => "btn btn-success", "name" => "enviar"]) !!}
    <a class="btn btn-danger" href="{{ route('admin.home') }}">Voltar</a>
</div> 
{!! Form::close() !!}

<!--Scripts-->
<script src="{{ asset('assets/js/mascaraMoeda.js') }}"></script>
@stop