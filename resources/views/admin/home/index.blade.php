@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Meus Estabelecimentos</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!--conteudo-->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $data['meusEstabelecimentosCadastrados'] }}</h3>
                        <p>Cadastrados: {{ $data['meusEstabelecimentosCadastrados'] }} de {{ $data['configuracoes'][0]['limite_estabelecimentos_anunciante'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-home"></i>
                    </div>
                    <a href="{{ route('admin.estab') }}" class="small-box-footer">
                        Mais <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--Proxima janela-->
    <div class="col-md-4">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Meus Anuncios</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!--conteudo-->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $data['meusAnunciosCadastrados'] }}</h3>
                        <p>Cadastrados: {{ $data['meusAnunciosCadastrados'] }} de {{ $data['configuracoes'][0]['limite_anuncios_anunciante'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pricetags"></i>
                    </div>
                    <a href="{{ route('admin.anuncio') }}" class="small-box-footer">
                        Mais <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--Proxima janela-->
</div>
    <!-- -->
    <!-- ADMINISTRACAO -->
    <!-- -->
@can('isAdmin')
<div class="row">
    <div class="col-md-4">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Estabelecimentos Cadastrados</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!--conteudo-->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>{{ $data['estabelecimentosCadastrados'] }}</h3>
                        <p>Cadastrados hoje: {{ $data['estabelecimentosCadastradosHoje'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-people"></i>
                    </div>
                    <a href="{{ route('admin.estab') }}" class="small-box-footer">
                        Mais <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--Proxima janela-->
    <div class="col-md-4">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Usuários Cadastrados</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!--conteudo-->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>{{ $data['usuariosCadastrados'] }}</h3>
                        <p>Cadastrados hoje: {{ $data['usuariosCadastradosHoje'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-people"></i>
                    </div>
                    <a href="{{ route('admin.user') }}" class="small-box-footer">
                        Mais <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--Proxima janela-->
    <div class="col-md-4">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Anunciantes Ativos</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!--conteudo-->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>{{ $data['anunciantesCadastrados'] }}</h3>
                        <p>Cadastrados hoje: {{ $data['anunciantesCadastradosHoje'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-people"></i>
                    </div>
                    <a href="{{ route('admin.user') }}" class="small-box-footer">
                        Mais <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--Proxima janela-->
    <div class="col-md-4">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Anuncios Cadastrados</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <!--conteudo-->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>{{ $data['anunciosCadastrados'] }}</h3>
                        <p>Cadastrados hoje: {{ $data['anunciosCadastradosHoje'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pricetags"></i>
                    </div>
                    <a href="{{ route('admin.anuncio') }}" class="small-box-footer">
                        Mais <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--Proxima janela-->
</div>
@endcan



@stop