@extends('adminlte::page')

@section('title')
{{ $title }}
@stop

@section('content_header')
{!! App\Http\Controllers\Vendor::addMenuLocalizacaoPainel( array('Início' => 'site.home', $title => 'admin.ajuda'), 'glyphicon glyphicon-home') !!}
@stop

@section('content')
<div>
    <div class="alert alert-danger" data-pg-collapsed> 
        <strong>Olá {{ auth()->user()->name }}!</strong> Precisa de ajuda? Acesse a nossa página de perguntas
        frequentes, lá você encontrará dúvidas que outros usuários já tiveram e já foram respondidas.
        <br/><br/>
        <div class="text-center">
            <a class="btn btn-danger" style="text-decoration: none" href="{{ route('admin.ajuda') }}">Ajuda - Perguntas frequentes</a>
        </div>
        <hr/>
        Se você <strong>não encontrou lá a sua dúvida, tem alguma sugestão, crítica, elogio</strong>, ou necessita de algum contato
        com os responsáveis pela plataforma, entre em contato conosco por <strong>e-mail</strong>.
        <br/><br/>
        <div class="text-center">
            <button type="button" class="btn btn-danger" id="btn-ver-email" title="Ver e-mail" onclick="mostrarEmail()">Ver e-mail</button>
        </div>
    </div>
    <div class="alert alert-success text-center" id="alert-email" data-pg-collapsed style="display: none">
        <strong>{{ $email }}</strong>
    </div>
</div>
    <script>
        function mostrarEmail(){
            $('#alert-email').slideToggle();
            if($('#btn-ver-email').text() === 'Ver e-mail'){
                $('#btn-ver-email').text('Ocultar e-mail');
            }else{
                $('#btn-ver-email').text('Ver e-mail');
            }
        }
    </script>
@stop