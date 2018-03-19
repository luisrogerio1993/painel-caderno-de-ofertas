@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.anuncio'), 'glyphicon glyphicon-tag') !!}

{!! Form::open(["route" => "admin.user.search", "class" => "sidebar-form col-xs-5 col-lg-2 col-md-3 pull-right form-search" ]) !!}
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
@if(!empty($users))
<table class="table  table-striped text-center">
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Nome</th>
            <th scope="col">Anunciante?</th>
            <th scope="col">Estabelecimentos</th>
            <th scope="col">Anuncios</th>
            <th scope="col">Desde de</th>
            <th scope="col" colspan="2">Opções</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <th scope="row">
                    <img class="img-rounded" src="{{ $user->image != null ? url(config('constantes.DESTINO_IMAGE_USUARIO').'\\'.$user->image) : url(config('constantes.DEFAULT_IMAGE_USUARIO')) }}" width="65" alt="Imagem Usuário"/>
                </th>
                <td>{{ $user->name }}</td>
                <td>
                    @if ($user->getTotalEstabelecimentos() > 0 && $user->getTotalAnuncios() > 0)
                        Sim
                    @else
                        Não
                    @endif
                </td>
                <td>{{ $user->getTotalEstabelecimentos() }}</td>
                <td>{{ $user->getTotalAnuncios() }}</td>
                <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                <td>
                    <a href="{{ route('admin.user.editar', $user->id) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
                </td>
                <td>
                    {!! Form::open(["route" => ["admin.user.deletar", $user->id], "method" => "DELETE", "id" => "formDelete-$user->id" ]) !!} {!! Form::close() !!}
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-{{ $user->id }} "><span class='glyphicon glyphicon-trash'></span></button>
                    {!! App\Http\Controllers\Vendor::addModalView("Ação Irreversível!", "Deletar usuário: $user->name?", "danger", "modal-".$user->id, "Cancelar", "formDelete-$user->id") !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="text-center">
    {{ $users->links() }}
</div>

@else
<script type="text/javascript">
    $(document).ready(function () {
        $('#modal-info').modal('show');
    });
</script>

{!! App\Http\Controllers\Vendor::addModalView("Ainda não tem usuários cadastrados", "Nenhum usuário cadastrado.", "danger", "modal-info", "Entendi") !!}

@endif
@stop