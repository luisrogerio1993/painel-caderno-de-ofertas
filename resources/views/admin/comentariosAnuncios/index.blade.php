@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.comentario'), 'fa-ion ion-ios-chatboxes') !!}

{!! Form::open(["route" => "admin.comentario.search", "class" => "sidebar-form col-xs-5 col-lg-2 col-md-3 pull-right form-search" ]) !!}
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
@if(!empty($comentarios))
<table class="table table-striped tables-listagens-principais">
    <thead>
        <tr>
            <th scope="col">Anúncio</th>
            <th scope="col">Usuário</th>
            <th scope="col">Comentário</th>
            <th scope="col">Avaliação</th>
            <th scope="col">Data criação</th>
            <th scope="col" colspan="2">Opções</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($comentarios as $comentario)
        <tr>
            <td>
                <a href="{{ route('admin.anuncio.editar', $comentario->getAnuncio()->id) }}">{{ $comentario->getAnuncio()->nome }}</a>
            </td>
            <td>
                <a href="{{ route('admin.estab.editar', $comentario->getUser()->id) }}">{{ $comentario->getUser()->name }}</a>
            </td>
            <td>{{ str_limit($comentario->comentario, 100) }}</td>
            <td class="td-star-comments">
                @for($i=0; $i<$comentario->quantidade_estrelas_avaliacao; $i++)
                    <i class="fa fa-fw fa-ion ion-star text-yellow"></i>
                @endfor
            </td>
            <td>{{ $comentario->created_at }}</td>
    <td>
       <a href="{{ route('admin.comentario.editar', $comentario->id) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
</td>
<td>
{!! Form::open(["route" => ["admin.comentario.deletar", $comentario->id], "method" => "DELETE", "id" => "formDelete-$comentario->id" ]) !!} {!! Form::close() !!}
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-{{ $comentario->id }} "><span class='glyphicon glyphicon-trash'></span></button>
{!! App\Http\Controllers\Vendor::addModalView("Ação Irreversível!", "Deletar comentário de {$comentario->getUser()->name}", "danger", "modal-".$comentario->id, "Cancelar", "formDelete-$comentario->id") !!}
</td>
</tr>
@endforeach
</tbody>
</table>
<div class="text-center">
{{ $comentarios->links() }}
</div>

@else
<script type="text/javascript">
    $(document).ready(function () {
        $('#modal-info').modal('show');
    });
</script>

{!! App\Http\Controllers\Vendor::addModalView("Ainda não tem comentários cadastrados", "Nenhum comentário cadastrado.", "danger", "modal-info", "Entendi") !!}
@endif
@stop