<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
     /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('login', function () {
            $conditon = false;

            // check if the user is authenticated
            if (Auth::check()) {
                // check if the user has a subscription
                $condition = Auth::user()->isSubscribed;
            }

            return "<?php if ($condition) { ?>";
        });

        Blade::directive('logoff', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endlogin', function () {
            return "<?php } ?>";
        });
    }
}
