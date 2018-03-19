<?php

namespace App\Http\Middleware\Authorize\Users;

use Closure;
use Auth;

class isAdmin
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
        }
        
        return redirect()->route('admin.home')->with('messageReturn', ['status' => false, 'messages' => ['Você não pode executar essa ação.',]]);
    }
}
