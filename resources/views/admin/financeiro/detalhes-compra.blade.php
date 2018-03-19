@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', 'Meus Créditos' => 'admin.financeiro', 'Histórico de compras' => 'admin.financeiro.historico'), 'glyphicon glyphicon-home') !!}
@stop

@section('content')
<table class="table table-striped text-center">
    <thead>
        <tr>
            <th scope="col">Nome do item</th>
            <th scope="col">Quantidade</th>
            <th scope="col">Valor</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($produtos as $produtoCompra)
        <tr>
            <td>{{ $produtoCompra->getNomeProduto($produtoCompra->produto_id) }}</td>
            <td>{{ $produtoCompra->quantidade }}</td>
            <td>R$ {{ number_format($produtoCompra->valor_unitario, 2, ',', '.') }}</td>
        </tr>
        @empty
            <h1>Nenhum produto</p>
        @endforelse
    </tbody>
</table>
@stop