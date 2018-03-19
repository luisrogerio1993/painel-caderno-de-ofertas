<?php

namespace App\Http\Middleware\Authorize\Financeiro;

use Closure;
use Auth;

class financeiroShow
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
        if(Auth::user()->is_admin == true){
            return $next($request);
        }else if (Auth::user()->getTotalAnuncios() < 1){ //ao menos 1 anuncio
            return redirect()->route('admin.anuncio')->with('messageReturn', ['status' => false, 'messages' => ['Você precisa ter ao menos um anúncio cadastrado.',]]);
        }
        
        return $next($request);
    }
}