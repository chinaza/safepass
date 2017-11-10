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
        'App\Events\EventEvent' => [
            'App\Listeners\EventListenerListener',
        ],
        'App\Events\PasswordCreatedEvent' => [
            'App\Listeners\PasswordCreatedListener',
        ],
        'App\Events\PasswordModifiedEvent' => [
            'App\Listeners\PasswordModifiedListener',
        ],
        'App\Events\MemberAddedEvent' => [
            'App\Listeners\MemberAddedListener',
        ],
        'App\Events\MemberRemovedEvent' => [
            'App\Listeners\MemberRemovedListener',
        ],
        'App\Events\RoleChangedEvent' => [
            'App\Listeners\RoleChangedListener',
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

        //
    }
}
