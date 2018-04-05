@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.anuncio'), 'glyphicon glyphicon-tag') !!}

{!! Form::open(["route" => "admin.anuncio.search", "class" => "sidebar-form col-xs-5 col-lg-2 col-md-3 pull-right form-search" ]) !!}
<div class="input-group">
    {!! Form::text("key-search", null, ["class" => "form-control", "placeholder" => trans('auth.search'), "title" => trans('auth.search')]) !!}
    <span class="input-group-btn">
        <button type="submit" name="search" id="search-btn" class="btn btn-flat">
            <i class="fa fa-search"></i>
        </button>
    </span>
</div>
{!! Form::close() !!}
@stop

@section('content')
<a href="{{ route('admin.anuncio.cadastro') }}" class="btn btn-success btn-acima-form"><span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
@if(count($anuncios) > 0)
<table class="table table-striped tables-listagens-principais">
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Nome</th>
            <th scope="col">Categoria</th>
            <th scope="col">Valor (R$)</th>
            <th scope="col">Valido Até</th>
            <th scope="col">Anuncio</th>
            <th scope="col" colspan="2">Opções</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($anuncios as $anuncio)
        <tr>
            <th scope="row">
                <img class="img-rounded" src="{{ $anuncio->image != null ? url(config('constantes.DESTINO_IMAGE_ANUNCIO').'\\'.$anuncio->image) : url(config('constantes.DEFAULT_IMAGE_ANUNCIO')) }}" width="65" alt="Imagem Estabelecimento"/>
            </th>
            <td>{{ $anuncio->nome }}</td>
            <td>{{ $anuncio->getCategoriaAnuncio['nome'] }}</td>
            <td>{{ number_format($anuncio->valor_atual, 2, '.', '') }}</td>
            <td>{{ date('d/m/Y', strtotime($anuncio->anuncio_valido_ate)) }}</td>
            <td>{{ $anuncio->getTipoAnuncio['nome'] }}</td>
            <td>
                <a href="{{ route('admin.anuncio.editar', $anuncio->id) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
            </td>
            <td>
                {!! Form::open(["route" => ["admin.anuncio.deletar", $anuncio->id], "method" => "DELETE", "id" => "formDelete-$anuncio->id" ]) !!} {!! Form::close() !!}
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-{{ $anuncio->id }} "><span class='glyphicon glyphicon-trash'></span></button>
                {!! App\Http\Controllers\Vendor::addModalView("Ação Irreversível!", "Deletar anúncio: $anuncio->nome?", "danger", "modal-".$anuncio->id, "Cancelar", "formDelete-$anuncio->id") !!}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="text-center">
    {{ $anuncios->links() }}
</div>

@else
<script type="text/javascript">
    $(document).ready(function () {
        $('#modal-info').modal('show');
    });
</script>
{!! App\Http\Controllers\Vendor::addModalView("Você ainda não tem anúncios cadastrados", "Cadastre novos anúncios para aumentar sua exposição no mercado local.", "danger", "modal-info", "Entendi") !!}

@endif
@stop