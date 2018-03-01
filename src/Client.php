<?php

namespace Ohffs\LaravelTransmission;

use Zttp\Zttp;

class Client
{
    protected $hostname;

    protected $port;

    protected $username;

    protected $password;

    protected $session;

    public function __construct($hostname = null, $port = null)
    {
        $this->hostname = config('transmission.hostname', $hostname);
        $this->port = config('transmission.port', $port);
    }

    public function authenticate($username = null, $password = null)
    {
        $this->username = config('transmission.username', $username);
        $this->password = config('transmission.password', $password);
        return $this;
    }

    public function all()
    {
        // if $this->username
        //      Zttp:: ...
        // else
        //      Zttp:: ...
        // return collect (results -> TorrentEntry)
    }

    public function find($id)
    {
        // if $this->username
        //      Zttp:: ...
        // else
        //      Zttp:: ...
        // return result -> TorrentEntry
    }

    public function findOrFail($id)
    {
        // if not this->find -> throw Exception
        // return result
    }

    protected function callApi($message)
    {
        // Zttp:: ...
    }
}
