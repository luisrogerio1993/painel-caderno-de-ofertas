<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Auth;
use App\Models\Estabelecimentos;
use App\Models\Anuncios;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model\XXX' => 'App\Policies\XXX',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        
        /*
         * PARA MENU ADMINLTE
         */
        Gate::define('isAdmin', function () {
            return Auth::user()->is_admin;
        });
        
        Gate::define('isAnunciante', function () {
            $quantEstabelecimentos = Estabelecimentos::where('user_id', '=', Auth::user()->id)->count();
            return $quantEstabelecimentos > 0 ? true : false; 
        });
        
        Gate::define('userTemAnuncios', function () {
            return (Auth::user()->getTotalAnuncios() > 0) ? true : false;
        });
    }
}
