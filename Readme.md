WIP

# Laravel API wrapper for Transmission

This is a (for now) very basic wrapper around the [Transmission](https://transmissionbt.com/) bittorrent
client's API.

For now it only supports getting a list of all current torrents, fetching a specific torrent and adding
a new torrent in 'paused' mode.  This was all I needed for my purposes in [transcopy](https://github.com/ohnotnow/transcopy).

# Installing

Assuming you have [composer](https://getcomposer.org/) available :

```
composer require ohnotnow/laravel-transmission
```

Assuming you are on Laravel 5.5+ the package should be auto-discovered.  If not you'll have to manually add it to the Providers/Aliases to add to your `config/app.php` :

```php
'providers' => [
...
    Ohffs\LaravelTransmission\TransmissionServiceProvider::class,
...
],
'aliases' => [
...
    'Transmission' => Ohffs\LaravelTransmission\Transmission::class,
...
]
```

# Usage

You need to set some environment variables first :

```
TRANSMISSION_HOST=127.0.0.1
TRANSMISSION_PORT=9091
TRANSMISSION_USERNAME=
TRANSMISSION_PASSWORD=
```

Then in a somewhere in your project :

```php
use Ohffs\LaravelTransmission\Client;

class Whatever
{
    protected $transmission;

    public function __construct(Client $transmission)
    {
        $this->transmission = $transmission;
    }

    public function index()
    {
        return $this->transmission->all();
        // or if you are using a username/password
        return $this->transmission->authenticate()->all();
    }

    public function show($id)
    {
        return $this->transmission->find($id);
        // or again if using a username/password
        return $this->transmission->authenticate()->find($id);
    }
}
```

The find() method returns a single TorrentEntry class, the all() method returns a
collection of them.  The data is in the format provided by Tranmissions API - so for example
the 'percentDone' is given as a float from 0.0 to 1.0 so you'd have to multiply it by 100 to get
a 'realistic' percentage.  You can get the data from them :

```php
$torrent = $this->transmission->find(1);
echo $torrent->toArray();
/*
 'name' => 'Some Exciting File',
 'id' => 1,
 'doneDate' => 0,
 'eta' => 1000,
 'haveValid' => 0,
 'rateDownload' => 0,
 'rateUpload' => 0,
 'status' => 2,
 'totalSize' => 364514248,
 'downloadDir' => '/tmp/torrents',
 'percentDone' => 0.3,
 */

// And you can also get those as attributes on the object, eg :

echo $torrent->name;
// 'Some Exciting File'
```

Using the collection from all() you can use all of Laravel's collection methods of course.  For example,
to get a list of torrents which are still downloading :

```php
$stillDownloading = $this->transmission->all()->filter(function ($torrent, $key) {
    return $torrent->percentDone < 1;
});
```
