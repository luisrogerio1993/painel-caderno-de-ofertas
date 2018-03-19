<?php

namespace App\Http\Middleware\Authorize\Anuncios;

use Closure;
use Auth;
use App\Models\Configuracoes;

class anunciosLimit
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
        $limiteAnuncios = Configuracoes::find(1)->limite_anuncios_anunciante;
        if (Auth::user()->getTotalEstabelecimentos() >= $limiteAnuncios && !Auth::user()->is_admin){
            return redirect()->route('admin.anuncio')->with('messageReturn', ['status' => false, 'messages' => ['Limite de anúncios cadastrados atingido.', '( Limite: '.$limiteAnuncios.' )']]);
        }
        
        return $next($request);
    }
}
