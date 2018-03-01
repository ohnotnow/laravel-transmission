<?php

use Illuminate\Support\Facades\Facade;
use Ohffs\LaravelTransmission\Client;

class Transmission extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return Client::class; }
}
