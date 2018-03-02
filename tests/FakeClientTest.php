<?php

namespace Tests;

use Ohffs\LaravelTransmission\FakeClient;

class FakeClientTest extends TestCase
{
    use CommonTests;

    protected function getClient()
    {
        return app(FakeClient::class);
    }
}
