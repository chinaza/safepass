<?php

namespace App\Listeners;

use App\Events\MemberRemoved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertMemberRemoved
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
     * @param  MemberRemoved  $event
     * @return void
     */
    public function handle(MemberRemoved $event)
    {
        //
    }
}
