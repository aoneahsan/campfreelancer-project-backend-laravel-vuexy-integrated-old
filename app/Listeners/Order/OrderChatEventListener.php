<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderChatEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderChatEventListener
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
     * @param  OrderChatEvent  $event
     * @return void
     */
    public function handle(OrderChatEvent $event)
    {
        //
    }
}
