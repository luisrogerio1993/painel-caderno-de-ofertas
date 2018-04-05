<span class="label label-warning">!</span>
</a>
<ul class="dropdown-menu">
    @if($configuracoes->configuracao_aviso == 2)
        <li class="header">Notificação Crítica</li>
    @else
        <li class="header">Notificação Importante</li>
    @endif
    <li>
        <ul class="menu">
            <!-- notificacao normal -->
            @if($configuracoes->configuracao_aviso != 0)
                <li class="btn btn-default" style="white-space: normal;">
                    <i class="fa fa-{{ $configuracoes->configuracao_aviso == 1 ? 'info text-yellow' : 'warning text-red' }} pull-left" style="font-size: 30px;"></i> {!! nl2br($configuracoes->texto_aviso) !!}
                </li>
            @endif
            <!-- Creditos abaixo de R$10 -->
            @if($user->credito_disponivel < $creditoMinimoAviso)
                <li class="btn btn-default" style="white-space: normal;">
                    <i class="fa fa-info text-yellow pull-left" style="font-size: 30px;"></i>
                    Aviso: Você é um anunciante e seus créditos estão abaixo de R${{ $creditoMinimoAviso }}, 
                    recarregue e evite que seus anúncios parem de ser exibidos na plataforma.
                    <br/>
                    <br/>
                    <a href="{{ route('admin.financeiro') }}" class="btn btn-default">Recarregar</a>
                </li>
            @endif
        </ul>
    </li>
</ul>