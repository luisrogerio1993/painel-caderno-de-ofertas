<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title }}</title>
        <!--Favicon-->
        <link rel="icon" type="image/png" href="{{ asset('/assets/imgs/favicon.png') }}">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ url('/assets/css/folhaCss.css') }}">
        <link rel="stylesheet" href="{{ url('/assets/css/navbarHome.css') }}">
    </head>
    <body>
        <!--nav bar-->
        <nav class="navbar navbar-default" role="navigation" data-pg-collapsed> 
            <div class="container-fluid"> 
                <div class="navbar-header navbar-red"> 
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-bar"> 
                        <span class="sr-only">Toggle navigation</span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                    </button>             

                    <a class="navbar-brand active" href="{{ route('admin.home') }}"><b>Caderno</b> de Ofertas</a> 
                </div>   
                <div class="collapse navbar-collapse" id="nav-bar"> 
                    <ul class="nav navbar-nav"> 
                        <li class="active">
                            <a href="#">Perfect Solutions</a>
                        </li>                                                   
                    </ul>   
                    @if (Route::has('login'))
                    <ul class="nav navbar-nav navbar-right"> 
                        @auth
                        <li class="active">
                            <a href="{{ route('admin.perfil.editar') }}">{{ Auth::user()->name }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.home') }}">Painel</a>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('login') }}"><span class="glyphicon glyphicon-share-alt"></span> Entrar</a>
                        </li>                                  
                        <li>                                  
                            <a href="{{ route('register') }}"><span class="glyphicon glyphicon-menu-hamburger"></span> Registrar-me</a>
                        </li>                                  
                        @endauth
                    </ul>
                    @endif
                </div>         
            </div>     
        </nav>
        <!--/nav bar-->
        <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
        @include('includes.message-return')
        <!--conteudo-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-xs-12">
                    <center>
                        <img src="{{ url('/assets/imgs/home-app.png') }}" class="img-responsive" alt="Imagem Home" width="500px">            
                    </center>
                </div>
                <div class="col-md-4 col-xs-12">
                    <h3 class="text-center">Plataforma</h3>
                    <h1 class="text-center"><b>Caderno</b> de Ofertas</h1>
                    <h3><span class="glyphicon glyphicon-ok-sign"></span> As melhores ofertas para o usuário</h3>
                    <h3><span class="glyphicon glyphicon-ok-sign"></span> Os melhores preços para anunciantes</h3>
                    <hr/>
                    <h3>Sem mais, confira:</h3>
                    <br/>
                    <div class="row text-center">
                        <a class="btn btn-default btn-lg" href="#">Google Play Store</a>
                    </div>             
                </div>
            </div>
        </div>
        <!-- Caderno JS-->
        <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    </body>
</html>
