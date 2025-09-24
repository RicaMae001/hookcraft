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
        // Share cart count with all views, based on your DB schema
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                // Get the user's latest cart id (table: cart -> id, user_id)
                $cartId = DB::table('cart')
                    ->where('user_id', Auth::id())
                    ->orderByDesc('id')          // in case multiple carts exist
                    ->value('id');

                // Sum quantities from cart_item for that cart (table: cart_item -> cart_id, quantity)
                if ($cartId) {
                    $cartCount = (int) DB::table('cart_item')
                        ->where('cart_id', $cartId)
                        ->sum('quantity');
                }
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
