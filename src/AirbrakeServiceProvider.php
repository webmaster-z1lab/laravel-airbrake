<?php

namespace Kouz\Providers;

use Airbrake\Notifier;
use Illuminate\Support\ServiceProvider;

class AirbrakeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/airbrake.php' => config_path('airbrake.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Airbrake\Notifier', function ($app) {
            $handler = new AirbrakeHandler($app);

            return $handler->handle();
        });
    }
}
