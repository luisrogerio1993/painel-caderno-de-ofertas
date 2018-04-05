<?php

namespace App\Http\Middleware\Authorize\Perfil;

use Closure;
use Auth;

class perfilImageModify
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
        } else if (Auth::user()->image != $request->only('image')['image']) {
            //nome imagem incorreta
            return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => false, 'messages' => ['Nome da imagem incorreta.',]]);
        }
        return $next($request);
    }
}
