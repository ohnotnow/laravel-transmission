<?php

namespace Tests;

use Ohffs\LaravelTransmission\FakeClient;

class ClientFakeTest extends TestCase
{
    use CommonTests;

    protected function getClient()
    {
        return app(FakeClient::class);
    }
}
