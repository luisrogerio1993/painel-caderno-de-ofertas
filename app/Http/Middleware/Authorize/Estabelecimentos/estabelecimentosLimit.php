<?php

namespace App\Http\Middleware\Authorize\Estabelecimentos;

use Closure;
use Auth;
use App\Models\Configuracoes;

class estabelecimentosLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $limiteEstabelecimentos = Configuracoes::find(1)->limite_estabelecimentos_anunciante;
        if (Auth::user()->getTotalEstabelecimentos() >= $limiteEstabelecimentos && !Auth::user()->is_admin){
            return redirect()->route('admin.estab')->with('messageReturn', ['status' => false, 'messages' => ['Limite de estabelecimentos cadastrados atingido.', '( Limite: '.$limiteEstabelecimentos.' )']]);
        }
        
        return $next($request);
    }
}
