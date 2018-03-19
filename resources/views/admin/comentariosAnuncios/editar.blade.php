@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.comentario'), 'glyphicon glyphicon-comment') !!}
@stop

@section('content')
    {!! Form::model($comentario, ["route" => ["admin.comentario.editar", $comentario->id], "class" => "form-normal", "method" => "put"]) !!}
    <div class="form-group col-xs-12" style="padding: 0px">
        {!! Form::text("nome_anuncio", $comentario->getAnuncio()->nome, ["class" => "form-control", "placeholder" => trans('auth.nome_anuncio'), "title" => trans('auth.nome_anuncio'), "disabled"]) !!}
    </div>
    <div class="form-group col-xs-12" style="padding: 0px">
        {!! Form::text("nome_usuario", $comentario->getUser()->name, ["class" => "form-control", "placeholder" => trans('auth.nome_usuario'), "title" => trans('auth.nome_usuario'), "disabled"]) !!}
    </div>
    <div class="form-group col-xs-12 td-star-comments text-center" style="padding: 0px">
        Estrelas: 
        @for($i=0; $i<$comentario->quantidade_estrelas_avaliacao; $i++)
            <i class="fa fa-fw fa-ion ion-star text-yellow"></i>
        @endfor
    </div>
    <div class="form-group has-feedback col-xs-12 {{ $errors->has('comentario') ? 'has-error' : '' }}" style="padding: 0px">
        {!! Form::textarea("comentario", null, ["class" => "form-control", "placeholder" => trans('auth.comentario'), "title" => trans('auth.comentario'), "required"]) !!}
        <span class="glyphicon glyphicon-comment form-control-feedback"></span>
        @if ($errors->has('comentario'))
        <span class="help-block">
            <strong>{{ $errors->first('comentario') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group">
        {!! Form::submit("Atualizar", ["class" => "btn btn-success", "name" => "enviar"]) !!}
        <a class="btn btn-danger" href="{{ route('admin.comentario') }}">Voltar</a>
    </div> 
{!! Form::close() !!}
@stop
