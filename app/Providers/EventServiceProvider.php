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
        'App\Events\PasswordCreated' => [
            'App\Listeners\AlertPasswordCreated',
        ],
        'App\Events\PasswordModified' => [
            'App\Listeners\AlertPasswordModified',
        ],
        'App\Events\MemberAdded' => [
            'App\Listeners\AlertMemberAdded',
        ],
        'App\Events\MemberRemoved' => [
            'App\Listeners\AlertMemberRemoved',
        ],
        'App\Events\RoleChanged' => [
            'App\Listeners\AlertRoleChanged',
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
