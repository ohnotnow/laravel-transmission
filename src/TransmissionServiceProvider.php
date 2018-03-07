<?php

namespace Ohffs\LaravelTransmission;

use Illuminate\Support\ServiceProvider;
use Ohffs\LaravelTransmission\Client;
use Ohffs\Transmission\Client as TransmissionClient;

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
                new TransmissionClient(
                    config('transmission.hostname'),
                    config('transmission.port'),
                    config('transmission.username'),
                    config('transmission.password')
                )
            );
        });
    }
}
