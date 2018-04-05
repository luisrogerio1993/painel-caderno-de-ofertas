@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', 'Meus Anúncios' => 'admin.anuncio', $title => 'admin.anuncio.clicks'), 'ion ion-mouse') !!}
@stop

@section('content')
@if(!empty($clicks))
<table class="table table-striped tables-listagens-principais">
    <thead>
        <tr>
            <th scope="col">Nome Usuário</th>
            <th scope="col">Tipo Anúncio</th>
            <th scope="col">Nome Anúncio</th>
            <th scope="col">Nome Estabelecimento</th>
            <th scope="col">IP</th>
            <th scope="col">Valor</th>
            <th scope="col">Data</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clicks as $click)
            <tr>
                <td>{{ $click->nome_usuario }}</td>
                <td>{{ $click->nome_tipo_anuncio }}</td>
                <td>{{ $click->nome_anuncio }}</td>
                <td>{{ $click->nome_estabelecimento }}</td>
                <td>{{ $click->ip_click }}</td>
                <td>{{ $click->valor_click }}</td>
                <td>{{ $click->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="text-center">
    {{ $clicks->links() }}
</div>

@else
<script type="text/javascript">
    $(document).ready(function () {
        $('#modal-info').modal('show');
    });
</script>

{!! App\Http\Controllers\Vendor::addModalView("Ainda não tem clicks cadastrados", "Nenhum click cadastrado.", "danger", "modal-info", "Entendi") !!}

@endif
@stop