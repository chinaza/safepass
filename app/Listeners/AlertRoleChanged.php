<?php

namespace App\Listeners;

use App\Events\RoleChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertRoleChanged
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RoleChanged  $event
     * @return void
     */
    public function handle(RoleChanged $event)
    {
        //
    }
}
