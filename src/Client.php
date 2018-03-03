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
        "id", "name", "status", "doneDate", "haveValid", "totalSize", "eta", "rateDownload", "rateUpload", "downloadDir", "percentDone"
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
        $response = $this->callApi('torrent-get', ['ids' => [$id], 'fields' => $this->defaultFields]);
        if (count($response->json()['arguments']['torrents']) == 0) {
            return null;
        }
        return new TorrentEntry($response->json()['arguments']['torrents'][0]);
    }

    public function findOrFail($id)
    {
        $torrent = $this->find($id);
        if (!$torrent) {
            throw new \RuntimeException('No such torrent');
        }
        return $torrent;
    }

    public function addPaused($filename)
    {
        $response = $this->callApi('torrent-add', ['filename' => $filename, 'paused' => true]);
        return new TorrentEntry($response->json()['arguments']['torrent-added']);
    }

    public function remove($id)
    {
        $response = $this->callApi('torrent-remove', ['ids' => [$id]]);
        return true;
    }
    protected function transmissionUrl()
    {
        return $this->hostname . ':' . $this->port . '/transmission/rpc';
    }

    protected function callApi($methodName, $arguments = [])
    {
        $response = $this->buildInitialRequest()->post(
            $this->transmissionUrl(),
            [
                'method' => $methodName,
                'arguments' => $arguments
            ]
        );

        if ($response->status() == 409) {
            $this->token = $response->header('X-Transmission-Session-Id');
            $response = $this->callApi($methodName, $arguments);
        }

        if ($response->status() == 401) {
            throw new \InvalidArgumentException('Authorisation Failed');
        }

        return $response;
    }

    protected function buildInitialRequest()
    {
        if ($this->username) {
            return Zttp::withBasicAuth($this->username, $this->password)
                    ->withHeaders(['x-transmission-session-id' => $this->token]);
        }
        return Zttp::withHeaders(['x-transmission-session-id' => $this->token]);
    }
}
