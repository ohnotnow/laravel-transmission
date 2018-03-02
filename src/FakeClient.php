<?php

namespace Ohffs\LaravelTransmission;

use Zttp\Zttp;

class FakeClient
{
    protected $hostname;

    protected $port;

    protected $username;

    protected $password;

    protected $session;

    protected $torrents;

    public function __construct($hostname = null, $port = null)
    {
        $this->hostname = config('transmission.hostname', $hostname);
        $this->port = config('transmission.port', $port);
        $this->torrents = collect([]);
    }

    public function authenticate($username = null, $password = null)
    {
        $this->username = config('transmission.username', $username);
        $this->password = config('transmission.password', $password);
        return $this;
    }

    public function all()
    {
        return collect($this->torrents->toArray());
    }

    public function find($id)
    {
        return $this->torrents->first(function ($torrent, $key) use ($id) {
            return $torrent->id === $id;
        });
    }

    public function findOrFail($id)
    {
        $torrent = $this->find($id);
        if (! $torrent) {
            throw new \RuntimeException('No such torrent');
        }
        return $torrent;
    }

    public function addPaused($filename)
    {
        $entry = new TorrentEntry([
            'name' => $filename,
            'id' => rand(1, 1000000),
        ]);
        $this->torrents->push($entry);
        return $entry;
    }

    public function remove($id)
    {
        $this->torrents = $this->torrents->filter(function ($torrent, $key) use ($id) {
            return $torrent->id != $id;
        });
    }

    protected function callApi($message)
    {
        // if $this->username
        //      Zttp:: ...
        // else
        //      Zttp:: ...

        return $result;
    }
}
