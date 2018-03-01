<?php

namespace Ohffs\LaravelTransmission;

use Illuminate\Support\ServiceProvider;
use Ohffs\LaravelTransmission\Client;

class TransmissionServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class);
    }
}
