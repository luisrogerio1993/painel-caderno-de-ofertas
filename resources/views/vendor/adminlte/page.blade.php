@extends('adminlte::master')

@section('adminlte_css')
<link rel="stylesheet"
      href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
@stack('css')
@yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
'boxed' => 'layout-boxed',
'fixed' => 'fixed',
'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
        @if(config('adminlte.layout') == 'top-nav')
        <nav class="navbar navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                        {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                    </a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
                @else
                <!-- Logo -->
                <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">{{ trans('auth.toggle_navigation') }}</span>
                    </a>
                    @endif
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">

                        <ul class="nav navbar-nav">
                            <li>
                                @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                <!-- User Account: style can be found in dropdown.less -->
                                <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                    <i class="fa fa-fw fa-power-off"></i> 
                                </a>
                                @else
                            <!--Notificações-->
                            @php($configuracoes = App\Models\Configuracoes::find(1))
                            @php($user = Auth::user())
                            @php($creditoMinimoAviso = 10)
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                    
                                <!--*-->{{-- 0 = Desativada / 1 = Importante / 2 = Crítica --}}
                                    @if ($configuracoes->configuracao_aviso != 0)
                                <!--*-->{{-- 0 = Usuários / 1 = Anunciantes / 2 = Usuários e Anunciantes / 3 = Administradores / 4 = Todos --}}
                                <!--*-->{{-- 0 = Usuários --}}
                                        @if($configuracoes->mostrar_aviso_para == 0 && !$user->isAnunciante())
                                            @include('includes.notificacoes')
                                <!--*-->{{-- 1 = Anunciantes --}}
                                        @elseif($configuracoes->mostrar_aviso_para == 1 && $user->isAnunciante() && !$user->is_admin )
                                            @include('includes.notificacoes')
                                <!--*-->{{-- 2 = Usuários e Anunciantes --}}
                                        @elseif($configuracoes->mostrar_aviso_para == 2 && (!$user->isAnunciante() || $user->isAnunciante()) && !$user->is_admin )
                                            @include('includes.notificacoes')
                                <!--*-->{{-- 3 = Administradores --}}
                                        @elseif($configuracoes->mostrar_aviso_para == 3 && $user->is_admin)
                                            @include('includes.notificacoes')
                                <!--*-->{{-- 4 = Todos --}}
                                        @elseif($configuracoes->mostrar_aviso_para == 4)
                                            @include('includes.notificacoes')
                                        @else
                                        <span class="label label-success"></span>
                                        </a>
                                            <ul class="dropdown-menu">
                                                <li class="header">Nenhuma Notificação</li>
                                            </ul>
                                        @endif
                                    <!--*-->{{-- For Anunciantes e estiver com creditos abaixo do limite --}}
                                    @elseif( $user->isAnunciante() && $user->credito_disponivel < $creditoMinimoAviso)
                                        @include('includes.notificacoes')
                                    @else
                                        <span class="label label-success"></span>
                                        </a>
                                            <ul class="dropdown-menu">
                                                <li class="header">Nenhuma Notificação</li>
                                            </ul>
                                    @endif
                            </li>
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ $user->image != null ? url(config('constantes.DESTINO_IMAGE_USUARIO').'\\'.$user->image) : url(config('constantes.DEFAULT_IMAGE_USUARIO')) }}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{ strtoupper($user->name) }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="{{ $user->image != null ? url(config('constantes.DESTINO_IMAGE_USUARIO').'\\'.$user->image) : url(config('constantes.DEFAULT_IMAGE_USUARIO')) }}" class="img-circle" alt="User Image">

                                        <p>
                                            {{ strtoupper($user->name) }} - 
                                            @if($user->can('isAdmin'))
                                                ADMINISTRADOR
                                            @elseif ($user->can('userTemAnuncios'))
                                                ANUNCIANTE
                                            @else
                                                USUÁRIO
                                            @endif
                                            <small>MEMBRO DESDE {{ date_format($user->created_at, 'd-m-Y') }}</small>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body">
                                        <div class="row">
                                            <!-- Não mostrar se ele não for anunciante -->
                                            @if( $user->can('userTemAnuncios') )
                                                <div class="col-xs-3 text-center">
                                                    <a href="{{ route('admin.perfil.editar') }}" title="Meu perfil">Perfil</a>
                                                </div>
                                                <div class="col-xs-6 text-center">
                                                    <a href="{{ route('admin.financeiro') }}" title="Meus créditos">Créditos - 
                                                        <span class="pull-right-container">
                                                            <span class="label label-success">R$ {{ number_format($user->credito_disponivel, 2, ',', '.') }}</span>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="col-xs-3 text-center">
                                                    <a href="{{ route('admin.perfil.info') }}" title="Informações da conta">Info</a>
                                                </div>
                                            @else
                                                <div class="col-xs-12 text-center">
                                                    <a href="{{ route('admin.perfil.editar') }}" title="Meu perfil">Perfil</a>
                                                </div>
                                            @endif
                                            <!--/ Não mostrar se ele não for anunciante -->
                                        </div>
                                        <!-- /.row -->
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('auth.log_out') }}
                                </a>
                                <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                    @if(config('adminlte.logout_method'))
                                    {{ method_field(config('adminlte.logout_method')) }}
                                    @endif
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @if(config('adminlte.layout') == 'top-nav')
            </div>
            @endif
        </nav>
    </header>

    @if(config('adminlte.layout') != 'top-nav')
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
            </ul>
            <!-- Mensagem melhor navegador compativel-->
            
            <div class="msg-navegador">
                {{ App\Http\Controllers\Vendor::avisoNavegadorCompativel() }}
            </div>
            <!--/ Mensagem melhor navegador compativel-->
            
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
    @endif

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @if(config('adminlte.layout') == 'top-nav')
        <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content">
                @include('includes.message-return')
                
                <!--Mensagem Tela Pequena-->
                @include('includes.message-tela-pequena')
                @yield('content')
            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
        </div>
        <!-- /.container -->
        @endif
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->
@stop

@section('adminlte_js')
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@stack('js')
@yield('js')

@stack('scriptsCadernoPage')
@stack('cssCadernoPage')
@stop
