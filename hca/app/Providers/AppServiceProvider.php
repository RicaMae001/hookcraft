<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS when in production (fixes asset URLs on Cloudflare Tunnel)
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        // Share cart count and auth status with all views
        View::composer('*', function ($view) {
            $cartCount = 0;
            $isLoggedIn = Auth::check();

            if ($isLoggedIn) {
                // Only get REGULAR cart (is_buy_now = 0), not buy-now carts
                $cartId = DB::table('cart')
                    ->where('user_id', Auth::id())
                    ->where('is_buy_now', 0)
                    ->orderByDesc('id')
                    ->value('id');

                // Sum quantities from cart_item for that cart
                if ($cartId) {
                    $cartCount = (int) DB::table('cart_item')
                        ->where('cart_id', $cartId)
                        ->sum('quantity');
                }
            }

            $view->with([
                'cartCount' => $cartCount,
                'isLoggedIn' => $isLoggedIn
            ]);
        });
    }
}