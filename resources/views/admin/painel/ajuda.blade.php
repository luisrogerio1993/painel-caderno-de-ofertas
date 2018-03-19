@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'site.home', $title => 'admin.ajuda'), 'glyphicon glyphicon-home') !!}
@stop

@section('content')
<div class="col-md-13">
    <h4 class="text-bold" style="margin-bottom: 25px">Perguntas frequentes</h4>
    @forelse($perguntas as $pergunta)
    <div class="box box-danger collapsed-box">
        <div class="box-header with-border" data-widget="collapse" style="cursor: pointer;">
            <h3 class="box-title">{{ $pergunta->pergunta }}</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="box-body" style="display: none;">
            Resposta: {{ $pergunta->resposta }}
        </div>
    </div>
    @empty
    <h3>Nenhuma pergunta frequente</h3>
    @endforelse
</div>
@stop