@extends('emails.template')
@section('conteudo')
<p>Olá {{ $name }}, seu pagamento foi aprovado!</p>
<p>A compra de referência {{ $referencia }} já está disponivel em sua conta.</p>
<p style="text-align: center;">
    <a href="{{ route('admin.financeiro.historico') }}" class="botao-email">Acessar painel</a>
</p>
<div class="row div-ajuda-link">
    <p>Se você estiver tendo problemas para clicar no botão "Acessar painel", copie e cole o URL abaixo em seu navegador:<br/>
        {{ route('admin.financeiro.historico') }}</p>
</div>
@stop