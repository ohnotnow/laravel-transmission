<?php

namespace Tests;

trait CommonTests
{
    /** @test */
    public function can_get_a_collection_of_all_torrents()
    {
        $client = $this->getClient();

        $originalTorrents = $client->all();

        $torrent = $client->addPaused(__DIR__ . '/data/asimov_foundation_archive_org.torrent');

        $torrents = $client->all();

        $this->assertEquals($originalTorrents->count() + 1, $torrents->count());
        $client->remove($torrent->id);
    }

    /** @test */
    public function can_get_a_single_torrent()
    {
        $client = $this->getClient();
        sleep(1); // the api can be a bit slow, so force a delay so the test has clean data :-/
        $torrent1 = $client->addPaused(__DIR__ . '/data/asimov_foundation_archive_org.torrent');

        $torrent = $client->find($torrent1->id);

        $this->assertEquals(
            [
              "doneDate" => 0,
              "eta" => -1,
              "haveValid" => 0,
              "id" => $torrent1->id,
              "name" => "IsaacAsimovFoundation6Of864kb",
              "rateDownload" => 0,
              "rateUpload" => 0,
              "status" => 2,
              "totalSize" => 364514248,
            ],
            $torrent->toArray()
        );
        $client->remove($torrent->id);
    }

    /** @test */
    public function trying_to_find_a_torrent_which_doesnt_exist_returns_null()
    {
        $client = $this->getClient();

        $torrent = $client->find(1234);

        $this->assertNull($torrent);
    }

    /** @test */
    public function we_can_ask_for_a_non_existent_torrent_to_throw_an_exception()
    {
        $client = $this->getClient();

        try {
            $torrent = $client->findOrFail(1234);
        } catch (\RuntimeException $e) {
            $this->assertEquals('No such torrent', $e->getMessage());
            return true;
        }

        $this->fail("An exception was expected, but wasn't thrown");
    }
}
