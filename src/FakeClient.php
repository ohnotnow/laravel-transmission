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
        $fileInfo = $this->extractTorrentInfo($filename);
        $entry = new TorrentEntry([
            'name' => $fileInfo['info']['name'],
            'id' => rand(1, 1000000),
            'doneDate' => 0,
            'eta' => -1,
            'haveValid' => 0,
            'rateDownload' => 0,
            'rateUpload' => 0,
            'status' => 2,
            'totalSize' => 364514248,
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

    protected function extractTorrentInfo($filename)
    {
        $encoder = new \PHP\BitTorrent\Encoder();
        $decoder = new \PHP\BitTorrent\Decoder($encoder);

        return $decoder->decodeFile($filename);
    }
}
