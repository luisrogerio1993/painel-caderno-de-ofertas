@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'admin.home', $title => 'admin.perfil.info'), 'glyphicon glyphicon-home') !!}
@stop

@section('content')
<table class="table table-striped text-center">
    <tr>
        <th scope="col" colspan="2">Estabelecimentos</th>
    </tr>
    <tr>
        <th scope="col" style="width: 50%">Total de estabelecimentos</th>
        <td>{{ $data['totalEstabelecimentos'] }}</td>
    </tr>
    <tr>
        <th scope="col" colspan="2">Anúncios</th>
    </tr>
    <tr>
        <th scope="col">Total de anúncios</th>
        <td>{{ $data['totalAnuncios'] }}</td>
    </tr>
    <tr>
        <th scope="col">Anuncios: Padrão</th>
        <td>{{ $data['anunciosPadrao'] }}</td>
    </tr>
    <tr>
        <th scope="col">Anuncios: Premium</th>
        <td>{{ $data['anunciosPremium'] }}</td>
    </tr>
    <tr>
        <th scope="col">Anuncios: Proximidade física</th>
        <td>{{ $data['anunciosProximidadeFisica'] }}</td>
    </tr>
    <tr>
        <th scope="col">Total de cliques</th>
        <td>{{ $data['totalClicksAnuncios'] }}</td>
    </tr>
    <tr>
        <th scope="col">Total de cliques hoje</th>
        <td>{{ $data['totalClicksAnunciosHoje'] }}</td>
    </tr>
    <tr>
        <th scope="col">Total de cliques na categoria: Padrão</th>
        <td>{{ $data['totalClicksPadrao'] }}</td>
    </tr>
    <tr>
        <th scope="col">Total de cliques na categoria: Premium</th>
        <td>{{ $data['totalClicksPremium'] }}</td>
    </tr>
    <tr>
        <th scope="col">Total de cliques na categoria: Proximidade física</th>
        <td>{{ $data['totalClicksProximidadeFisica'] }}</td>
    </tr>
    <tr>
        <th scope="col" colspan="2">Investimento</th>
    </tr>
    <tr>
        <th scope="col">Média de investimento por clique</th>
        <td>R$ {{ $data['mediaInvestimento'] }}</td>
    </tr>
    <tr>
        <th scope="col">Total de investimento em anúncios</th>
        <td>R$ {{ $data['totalInvestimento'] }}</td>
    </tr>
</table>
@stop