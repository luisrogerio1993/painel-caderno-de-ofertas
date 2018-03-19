@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', 'Meus Anuncios' => 'admin.anuncio', $title => 'admin.anuncio.cadastro'), 'glyphicon glyphicon-tag') !!}
@stop

@section('content')
@if (isset($anuncio))
    {!! Form::model($anuncio, ["route" => ["admin.anuncio.editar", $anuncio->id], "class" => "form-normal", "method" => "put", "files" => true]) !!}
    <div class="div-img" >
        <img id="div-img" class="img-rounded" src="{{ $anuncio->image != null ? url(config('constantes.DESTINO_IMAGE_ANUNCIO').'\\'.$anuncio->image) : url(config('constantes.DEFAULT_IMAGE_ANUNCIO')) }}" width="79" alt="Imagem Anuncio"/>
@else
    {!! Form::open(["route" => "admin.anuncio.cadastro", "class" => "form-normal", "files" => true]) !!}
    <div class="div-img" >
        <img id="div-img" class="img-rounded" src="{{ url(config('constantes.DEFAULT_IMAGE_ANUNCIO')) }}" width="79" alt="Imagem Anuncio"/>
@endif
    </div>
    <div class="form-group has-feedback col-xs-10 {{ $errors->has('image') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::file("image", ["class" => "form-control", "id" => "image", "onchange" => "PreviewImage(this.id);"]) !!}
        <span class="glyphicon glyphicon-picture form-control-feedback"></span>
        @if ($errors->has('image'))
        <span class="help-block">
            <strong>{{ $errors->first('image') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('estabelecimento_id') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::select("estabelecimento_id", $meusEstabelecimentos, null, ["class" => "form-control", "placeholder" => trans('auth.estabelecimento_id') ]) !!}
        @if ($errors->has('estabelecimento_id'))
        <span class="help-block">
            <strong>{{ $errors->first('estabelecimento_id') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('nome') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::text("nome", null, ["class" => "form-control", "placeholder" => trans('auth.full_name'), "required"]) !!}
        <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
        @if ($errors->has('nome'))
        <span class="help-block">
            <strong>{{ $errors->first('nome') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('categoria_anuncio_id') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::select("categoria_anuncio_id", $categoriasEstabelecimentos, null, ["class" => "form-control", "placeholder" => trans('auth.categoria') ]) !!}
        @if ($errors->has('categoria_anuncio_id'))
        <span class="help-block">
            <strong>{{ $errors->first('categoria_anuncio_id') }}</strong>
        </span>
        @endif
    </div>                
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('descricao') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::textarea("descricao", null, ["class" => "form-control", "placeholder" => trans('auth.descricao'), "rows" => "2" ]) !!}
        <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
        @if ($errors->has('descricao'))
        <span class="help-block">
            <strong>{{ $errors->first('descricao') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('valor_atual') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::text("valor_atual", null, ["class" => "form-control", "placeholder" => trans('auth.valor_atual'), "onkeypress" => "return(mascaraMoeda(this,'','.',event))", "required" ]) !!}
        <span class="glyphicon glyphicon-usd form-control-feedback"></span>
        @if ($errors->has('valor_atual'))
        <span class="help-block">
            <strong>{{ $errors->first('valor_atual') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('valor_original') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::text("valor_original", null, ["class" => "form-control", "placeholder" => trans('auth.valor_original'), "onkeypress" => "return(mascaraMoeda(this,'','.',event))", "required" ]) !!}
        <span class="glyphicon glyphicon-usd form-control-feedback"></span>
        @if ($errors->has('valor_original'))
        <span class="help-block">
            <strong>{{ $errors->first('valor_original') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('anuncio_valido_ate') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::text("anuncio_valido_ate", null, ["class" => "form-control", 
                                                    "placeholder" => trans('auth.anuncio_valido_ate'),
                                                    "max" => \Carbon\Carbon::now()->addWeek()->format('Y-m-d'), 
                                                    "min" => \Carbon\Carbon::now()->addDay()->format('Y-m-d'),
                                                    "onclick" => "(this.type='date')", "required" ]) !!}
        <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
        @if ($errors->has('anuncio_valido_ate'))
        <span class="help-block">
            <strong>{{ $errors->first('anuncio_valido_ate') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('tipo_anuncio') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::select("tipo_anuncio", $tiposAnuncio, null, ["class" => "form-control", "placeholder" => trans('auth.tipo_anuncio') ]) !!}
        @if ($errors->has('tipo_anuncio'))
        <span class="help-block">
            <strong>{{ $errors->first('tipo_anuncio') }}</strong>
        </span>
        @endif
    </div>
<div class="form-group col-xs-12" style="padding-left: 0px">
    @if (isset($anuncio))
        {!! Form::submit("Alterar", ["class" => "btn btn-success", "name" => "enviar"]) !!}
    @else
        {!! Form::submit("Cadastrar", ["class" => "btn btn-success", "name" => "enviar"]) !!}
    @endif
    <a class="btn btn-danger" href="{{ route('admin.anuncio') }}">Voltar</a>
</div> 
{!! Form::close() !!}

    <!--Scripts-->
    <script src="{{ asset('assets/js/previewImagemUploadEmDiv.js') }}"></script>
    <script src="{{ asset('assets/js/checarImageUploadExcedeLimite.js') }}"></script>
    <script src="{{ asset('assets/js/mascaraMoeda.js') }}"></script>
    
    <!--Funções-->
    {!! App\Http\Controllers\Vendor::addModalView("Imagem inválida", "A imagem enviada Excede o limite de upload (2MB). Selecione outra ou reduza o tamanho desta.", "Entendi") !!}
@stop