<?php

namespace Sykez\BetterPay;

use Sykez\BetterPay\BetterPay;
use Illuminate\Support\ServiceProvider;

class BetterPayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/betterpay.php', 'betterpay');

        $this->app->singleton(BetterPay::class, function() {
            return new BetterPay(...array_values(config('betterpay')));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/betterpay.php' => config_path('betterpay.php'),], 'betterpay');
        }
    }
}
