<span class="label label-warning">!</span>
</a>
<ul class="dropdown-menu">
    @if($configuracoes->configuracao_aviso == 1)
    <li class="header">Notificação Importante</li>
    @else
    <li class="header">Notificação Crítica</li>
    @endif
    <li>
        <ul class="menu">
            <li class="btn btn-default" style="white-space: normal;">
                <i class="fa fa-{{ $configuracoes->configuracao_aviso == 1 ? 'info text-yellow' : 'warning text-red' }} pull-left" style="font-size: 30px;"></i> {!! nl2br($configuracoes->texto_aviso) !!}
            </li>
        </ul>
    </li>
</ul>