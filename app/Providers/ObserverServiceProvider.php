<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Bookings;
use App\Observers\BookingsObserver;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Bookings::observe(BookingsObserver::class);
    }
}
