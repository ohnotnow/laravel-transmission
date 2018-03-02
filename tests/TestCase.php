<?php

namespace Tests;

use Ohffs\LaravelTransmission\TransmissionServiceProvider;
use Ohffs\LaravelTransmission\Transmission;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageAliases($app)
    {
        return [
            'Transmission' => Transmission::class,
        ];
    }

    protected function getPackageProviders($app)
    {
        return [TransmissionServiceProvider::class];
    }
}
