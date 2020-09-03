<?php

namespace App\Listeners;

use App\Events\NewChatEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewChatEventListener
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
     * @param  NewChatEvent  $event
     * @return void
     */
    public function handle(NewChatEvent $event)
    {
        //
    }
}
