@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.financeiro.historico'), 'glyphicon glyphicon-home') !!}
@stop

@section('content')
@if(count($userCompras) > 0)
    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th scope="col">Referencia</th>
                <th scope="col">Forma de pagamento</th>
                <th scope="col">Status</th>
                <th scope="col">Valor Total</th>
                <th scope="col">Data</th>
                <th scope="col">Detalhes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($userCompras as $userCompra)
            <tr>
                <th scope="row">
                        {{ $userCompra->referencia }}
                </th>
                <td>{{ $userCompra->getMetodoPagamento() }}</td>
                <td>{{ $userCompra->getStatus() }}</td>
                <td>R$ {{ number_format($userCompra->getValorTotal($userCompra->id), 2, ',', '.') }}</td>
                <td>{{ $userCompra->getDateCreate() }}</td>
                <td><a href="{{ route('admin.financeiro.detalhesCompra', $userCompra->id) }}" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-list"></span></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{ $userCompras->links() }}
    </div>
@else
    <script type="text/javascript">
        $(document).ready(function () {
            $('#modal-info').modal('show');
        });
    </script>
    {!! App\Http\Controllers\Vendor::addModalView("Você não tem compras realizadas", "Assim que realizar compras, você poderá acompanha-las nesta página.", "info", "modal-info", "Entendi") !!}
@endif
@stop