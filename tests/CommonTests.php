<?php

namespace Tests;

trait CommonTests
{
    /** @test */
    public function can_get_a_collection_of_all_torrents()
    {
        $client = $this->getClient();
        $client->add('test1.torrent');
        $client->add('test2.torrent');

        $torrents = $client->all();

        $this->assertCount(2, $torrents);
    }

    /** @test */
    public function can_get_a_single_torrent()
    {
        $client = $this->getClient();
        $torrent1 = $client->add('test1.torrent');
        $torrent2 = $client->add('test2.torrent');

        $torrent = $client->find($torrent1->torrent_id);

        $this->assertEquals('test1.torrent', $torrent->filename);
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
