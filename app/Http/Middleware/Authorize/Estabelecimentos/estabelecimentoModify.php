<?php

namespace App\Http\Middleware\Authorize\Estabelecimentos;

use Closure;
use Auth;

class estabelecimentoModify
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
        if (Auth::user()->is_admin == true) {
            return $next($request);
        } else if (!Auth::user()->emailVerificado()) {
            //se user com email não verificado
            return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => false, 'messages' => ['Antes disso, verifique seu email para ativar sua conta.',]]);
        }
        return $next($request);
    }
}
