<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\MessageSendEvent' => [
            'App\Listeners\MessageSendEventListener'
        ],
        'App\Events\NewChatEvent' => [
            'App\Listeners\NewChatEventListener'
        ],
        'App\Events\Order\OrderEvent' => [
            'App\Listeners\Order\OrderEventListener'
        ],
        'App\Events\Order\OrderChatEvent' => [
            'App\Listeners\Order\OrderChatEventListener'
        ],
        'App\Events\Order\UserNotificationEvent' => [
            'App\Listeners\Order\UserNotificationEventListener'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
