<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Model\Gig\GigCategory;
use App\Model\Gig\GigServiceType;
use App\Model\Gig\UserGig;
use App\Observers\Gig\GigCategoryObserver;
use App\Observers\Gig\GigServiceTypeObserver;
use App\Observers\Gig\UserGigObserver;
use App\Observers\User\UserObserver;
use App\User;

class ModelObserverProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Registring Observers to listen for model events
        User::observe(UserObserver::class);
        UserGig::observe(UserGigObserver::class);
        GigCategory::observe(GigCategoryObserver::class);
        GigServiceType::observe(GigServiceTypeObserver::class);
    }
}
