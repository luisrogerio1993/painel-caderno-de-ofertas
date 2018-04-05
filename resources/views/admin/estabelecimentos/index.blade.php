@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.estab'), 'glyphicon glyphicon-home') !!}

{!! Form::open(["route" => "admin.estab.search", "class" => "sidebar-form col-xs-5 col-lg-2 col-md-3 pull-right form-search" ]) !!}
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
<a href="{{ route('admin.estab.cadastro') }}" class="btn btn-success btn-acima-form"><span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
@if( count($estabelecimentos) > 0 )
<table class="table table-striped tables-listagens-principais">
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Nome</th>
            <th scope="col">Categoria</th>
            <th scope="col">Anúncios</th>
            <th scope="col">Verificado?</th>
            <th scope="col" colspan="2">Opções</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($estabelecimentos as $estabelecimento)
        <tr>
            <th scope="row">
                <img class="img-rounded" src="{{ $estabelecimento->image != null ? url(config('constantes.DESTINO_IMAGE_ESTABELECIMENTO').'\\'.$estabelecimento->image) : url(config('constantes.DEFAULT_IMAGE_ESTABELECIMENTO')) }}" width="65" alt="Imagem Estabelecimento"/>
            </th>
            <td>{{ $estabelecimento->nome }}</td>
            <td>{{ $estabelecimento->getCategoria->nome }}</td>
            <td>{{ $estabelecimento->getTotalAnuncios() }}</td>
            <td>{{ $estabelecimento->estabelecimento_verificado == true ? 'Verificado' : 'Em analise' }} </td>
            <td>
                <a href="{{ route('admin.estab.editar', $estabelecimento->id) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
            </td>
            <td>
                {!! Form::open(["route" => ["admin.estab.deletar", $estabelecimento->id], "method" => "DELETE", "id" => "formDelete-$estabelecimento->id" ]) !!} {!! Form::close() !!}
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-{{ $estabelecimento->id }} "><span class='glyphicon glyphicon-trash'></span></button>
                {!! App\Http\Controllers\Vendor::addModalView("Ação Irreversível!", "Deletar estabelecimento: $estabelecimento->nome?", "danger", "modal-".$estabelecimento->id, "Cancelar", "formDelete-$estabelecimento->id") !!}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="text-center">
    {{ $estabelecimentos->links() }}
</div>
@else
    <!-- Ir da pag Anuncios -> Estabelecimentos sem mostrar modal-->
    @if (url()->previous() != route('admin.anuncio'))
        <script type="text/javascript">
            $(document).ready(function () {
                $('#modal-info').modal('show');
            });
        </script>
        {!! App\Http\Controllers\Vendor::addModalView("Você ainda não tem estabelecimentos cadastrados", "Cadastre novos estabelecimentos para aumentar sua exposição no mercado local.", "danger", "modal-info", "Entendi") !!}
    @endif
@endif
@stop