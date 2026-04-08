<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        view()->composer('*', function ($view) {
            if (session()->has('user_login')) {
                $latestOrder = \Illuminate\Support\Facades\DB::table('order')
                    ->where('pelanggan', session()->get('user_name'))
                    ->orderBy('created_at', 'desc')
                    ->first();
                $view->with('latestOrderId', $latestOrder ? $latestOrder->id : null);
            }
        });
    }
}
