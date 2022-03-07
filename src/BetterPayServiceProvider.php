<?php

namespace Sykez\Betterpay;

use Sykez\Betterpay\Betterpay;
use Illuminate\Support\ServiceProvider;

class BetterpayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/betterpay.php', 'betterpay');

        $this->app->singleton(Betterpay::class, function () {
            return new Betterpay(...array_values(config('betterpay')));
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
