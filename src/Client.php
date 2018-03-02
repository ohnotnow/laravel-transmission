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

    protected $token;

    protected $defaultFields = [
        "id", "name", "status", "doneDate", "haveValid", "totalSize", "eta", "rateDownload", "rateUpload"
    ];

    public function __construct($hostname = null, $port = null)
    {
        $this->hostname = config('transmission.hostname', $hostname);
        $this->port = config('transmission.port', $port);
    }

    public function authenticate($username = null, $password = null)
    {
        $this->username = $username ?: config('transmission.username');
        $this->password = $password ?: config('transmission.password');
        return $this;
    }

    public function all()
    {
        $response = $this->callApi('torrent-get', ['fields' => $this->defaultFields]);
        $data = $response->json();
        $torrents = collect([]);
        foreach ($data['arguments']['torrents'] as $torrentData) {
            $torrents->push(new TorrentEntry($torrentData));
        }
        return $torrents;
    }

    public function find($id)
    {
        // result = this -> callApi(...)
        // return result -> TorrentEntry
    }

    public function findOrFail($id)
    {
        // if not this->find -> throw Exception
        // return result
    }

    public function add($filename)
    {
    }

    protected function transmissionUrl()
    {
        return $this->hostname . ':' . $this->port . '/transmission/rpc';
    }

    protected function callApi($methodName, $arguments = [])
    {
        $response = Zttp::withBasicAuth($this->username, $this->password)
                        ->withHeaders(['x-transmission-session-id' => $this->token])
                        ->post($this->transmissionUrl(), ['method' => $methodName, 'arguments' => $arguments]);

        if ($response->status() == 409) {
            $this->token = $response->header('X-Transmission-Session-Id');
            $response = $this->callApi($methodName, $arguments);
        }

        return $response;
    }
}
