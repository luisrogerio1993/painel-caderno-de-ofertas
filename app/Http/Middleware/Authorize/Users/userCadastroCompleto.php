<?php

namespace App\Http\Middleware\Authorize\Users;

use Closure;
use Auth;

class userCadastroCompleto
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
        }else if (Auth::user()->password == null){
            return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => true, 'messages' => ['Complete seu cadastro para usar nossa plataforma.',]]);
        }
        
        return $next($request);
    }
}
