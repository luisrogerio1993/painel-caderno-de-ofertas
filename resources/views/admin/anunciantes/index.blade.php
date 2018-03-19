@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.anuncio'), 'glyphicon glyphicon-tag') !!}
@stop

@section('content')
@if(!empty($anunciantes))
<table class="table  table-striped text-center">
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Nome</th>
            <th scope="col">Estabelecimentos</th>
            <th scope="col">Anuncios</th>
            <th scope="col">Desde de</th>
            <th scope="col">Opções</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($anunciantes as $anunciante)
            @if($anunciante->isAnunciante())
                <tr>
                    <th scope="row">
                        <img class="img-rounded" src="{{ $anunciante->image != null ? url(config('constantes.DESTINO_IMAGE_ANUNCIO').'\\'.$anunciante->image) : url(config('constantes.DEFAULT_IMAGE_ANUNCIO')) }}" width="79" alt="Imagem Estabelecimento"/>
                    </th>
                    <td>{{ $anunciante->name }}</td>
                    <td>{{ $anunciante->getTotalEstabelecimentos() }}</td>
                    <td>{{ $anunciante->getTotalAnuncios() }}</td>
                    <td>{{ date('d/m/Y', strtotime($anunciante->created_at)) }}</td>
                    <td>
                        <a href="{{ route('admin.user.editar', $anunciante->id) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
                        {!! Form::open(["route" => ["admin.user.deletar", $anunciante->id], "method" => "DELETE", "id" => "formDelete-$anunciante->id" ]) !!} {!! Form::close() !!}
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-{{ $anunciante->id }} "><span class='glyphicon glyphicon-trash'></span></button>
                        {!! App\Http\Controllers\Vendor::addModalView("Ação Irreversível!", "Deletar anúncio: $anunciante->name?", "danger", "modal-".$anunciante->id, "Cancelar", "formDelete-$anunciante->id") !!}
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
<div class="text-center">
    {{ $anunciantes->links() }}
</div>

@else
<script type="text/javascript">
    $(document).ready(function () {
        $('#modal-info').modal('show');
    });
</script>

{!! App\Http\Controllers\Vendor::addModalView("Atenção: Ação irreversível!", "Cadastre novos anuncios para aumentar sua exposição no mercado local.", "Entendi", "info") !!}

@endif
@stop