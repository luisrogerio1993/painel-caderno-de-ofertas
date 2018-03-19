<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // fired when the token could not be found in the request
        Event::listen('tymon.jwt.absent', function (){ return false; });

        // fired when the token has expired
        Event::listen('tymon.jwt.expired', function (){ return false; });

        // fired when the token is found to be invalid
        Event::listen('tymon.jwt.invalid', function (){ return false; });

        // fired if the user could not be found (shouldn't really happen)
        Event::listen('tymon.jwt.user_not_found', function (){ return false; });

        // fired when the token is valid (User is passed along with event)
        Event::listen('tymon.jwt.valid', function (){ return false; });
    }
}
