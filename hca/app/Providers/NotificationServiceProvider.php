<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // No longer needed - we're using NotificationHelper instead
        // You can delete this entire file or leave it empty
    }

    /**
     * Bootstrap services.
     */
    public function bootstrap(): void
    {
        //
    }
}