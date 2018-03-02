<?php

namespace Tests;

trait CommonTests
{
    /** @test */
    public function can_get_a_collection_of_all_torrents()
    {
        $client = $this->getClient();
        $torrent = $client->add(__DIR__ . '/data/asimov_foundation_archive_org.torrent');

        $torrents = $client->all();

        $this->assertCount(2, $torrents);
        $client->remove($torrent->id);
    }

    /** @test */
    public function can_get_a_single_torrent()
    {
        $client = $this->getClient();
        sleep(1);
        $torrent1 = $client->add(__DIR__ . '/data/asimov_foundation_archive_org.torrent');

        $torrent = $client->find($torrent1->id);

        $this->assertEquals('IsaacAsimovFoundation6Of864kb', $torrent->name);
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
