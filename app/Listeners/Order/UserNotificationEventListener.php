<?php

namespace App\Listeners\Order;

use App\Events\Order\UserNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserNotificationEventListener
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
     * @param  UserNotificationEvent  $event
     * @return void
     */
    public function handle(UserNotificationEvent $event)
    {
        //
    }
}
