<?php

namespace App\Http\Middleware\Authorize\Anuncios;

use Closure;
use Auth;

class anuncioModify {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::user()->is_admin == true) {
            return $next($request);
        } else if (Auth::user()->getTotalEstabelecimentos() == 0) {
            //ao menos 1 estabelecimento = true
            return redirect()->route('admin.estab')->with('messageReturn', ['status' => false, 'messages' => ['Cadastre ao menos 1 estabelecimento antes.',]]);
        }else if (!Auth::user()->temEstabelecimentoVerificado()) {
            //ao menos 1 estabelecimento revificado = true
            return redirect()->route('admin.estab')->with('messageReturn', ['status' => false, 'messages' => ['É necessário ter ao menos um estabelecimento verificado.',]]);
        }
        return $next($request);
    }
}
