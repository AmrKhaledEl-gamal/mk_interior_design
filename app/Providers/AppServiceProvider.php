<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
    public function boot(GeneralSettings $globalSettings): void
    {
        Blade::anonymousComponentPath(resource_path('views/admin/components'));

        view()->share('settings', $globalSettings);

        // Share global data with the front layout
        View::composer('*', function ($view) {
            $settings = app(GeneralSettings::class);

            // Cart Data
            $cartQuantity = \Darryldecode\Cart\Facades\CartFacade::getTotalQuantity();
            $cartItems = \Darryldecode\Cart\Facades\CartFacade::getContent()->sort();
            $cartTotal = \Darryldecode\Cart\Facades\CartFacade::getTotal();
            $cartSubTotal = \Darryldecode\Cart\Facades\CartFacade::getSubTotal();

            $view->with([
                'settings' => $settings,
                'cartQuantity' => $cartQuantity,
                'globalCartItems' => $cartItems,
                'cartTotal' => $cartTotal,
                'cartSubTotal' => $cartSubTotal,
            ]);
        });
    }
}
