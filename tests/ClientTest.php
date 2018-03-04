<?php

namespace Tests;

use Ohffs\LaravelTransmission\Client;

class ClientTest extends TestCase
{
    use CommonTests;

    protected function getClient()
    {
        return app(Client::class);
    }
}
