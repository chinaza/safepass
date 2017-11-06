<?php

namespace App\Listeners;

use App\Events\PasswordModified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertPasswordModified
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
     * @param  PasswordModified  $event
     * @return void
     */
    public function handle(PasswordModified $event)
    {
        //
    }
}
