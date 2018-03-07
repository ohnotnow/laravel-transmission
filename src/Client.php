<?php

namespace Ohffs\LaravelTransmission;

use Zttp\Zttp;
use Ohffs\Transmission\Client as TransmissionClient;

class Client
{
    protected $transmission;

    public function __construct(TransmissionClient $transmission)
    {
        $this->transmission = $transmission;
    }

    public function all()
    {
        return collect($this->transmission->all());
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this->transmission, $method)) {
            return call_user_func_array([$this->transmission, $method], $arguments);
        }
    }

    public function __get($attribute)
    {
        return $this->transmission->{$attribute};
    }
}
