<?php

namespace Ohffs\LaravelTransmission;

class TorrentEntry
{
    protected $attributes = [];

    public function __construct($transmissionData)
    {
        $this->attributes = $transmissionData;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return null;
    }

    public function toArray()
    {
        return $this->attributes;
    }
}
