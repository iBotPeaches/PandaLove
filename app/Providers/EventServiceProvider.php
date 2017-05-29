<?php

namespace PandaLove\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Onyx\User;
use PandaLove\Events\GoogleLoggedIn;
use PandaLove\Handlers\Events\SignIntoUser;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'event.name' => [
            'EventListener',
        ],

        GoogleLoggedIn::class => [
            SignIntoUser::class,
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        User::creating(function ($user) {
            if (User::count() == 0) {
                $user->admin = true;
            }
        });
    }
}
