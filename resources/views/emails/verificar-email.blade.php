@extends('emails.template')
@section('conteudo')
<p>Olá {{ $name }}, seu cadastro está quase pronto!</p>
<p>Agora você só precisa confirmar seu e-mail clicando no botão abaixo.</p>
<p style="text-align: center;">
    <a href="{{ route('admin.verificar.email', $email_token) }}" class="botao-email">Verificar e-mail</a>
</p>
<div class="row div-ajuda-link">
    <p>Se você estiver tendo problemas para clicar no botão "Verificar e-mail", copie e cole o URL abaixo em seu navegador:<br/>
        {{ route('admin.verificar.email', $email_token) }}</p>
</div>
@stop