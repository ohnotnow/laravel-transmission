<?php

namespace Ohffs\LaravelTransmission;

use Illuminate\Support\ServiceProvider;
use Ohffs\LaravelTransmission\Client;

class TransmissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/transmission.php' => config_path('transmission.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/transmission.php', 'transmission');
        $this->app->bind(Client::class, function ($app) {
            return new Client(
                config('transmission.hostname'),
                config('transmission.port')
            );
        });
    }
}
