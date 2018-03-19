@extends('emails.template')
@section('conteudo')
<p>Olá, você está recebendo este e-mail porque recebemos um pedido de redefinição de senha para sua conta.</p>
<p>Se você fez o pedido clique no botão baixo e siga os passos pedidos no painel.</p>
<p style="text-align: center;">
    <a href="{{ $actionUrl }}" class="botao-email">Verificar e-mail</a>
</p>
<div class="row div-ajuda-link">
    <p>
        Se você estiver tendo problemas para clicar no botão "Acessar painel", copie e cole o URL abaixo em seu navegador:<br/>
        {{ $actionUrl }}
    </p>
    <p>
        Se você não solicitou uma reinicialização da senha, ignore este e-mail, nenhuma ação adicional será necessária.
    </p>
</div>
@stop