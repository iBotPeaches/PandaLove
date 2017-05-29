<?php


class PandaApiTest extends TestCase
{
    public function testSeasonEndpoint()
    {
        $this->json('GET', '/h5/api/panda/seasons')
            ->seeJson([
                'error' => false,
            ]);
    }

    public function testCsrEndpoint()
    {
        $this->json('GET', '/h5/api/panda/csrs')
            ->seeJson([
                'error' => false,
            ]);
    }

    public function testLeaderboardEndpoint()
    {
        $this->json('GET', '/h5/api/panda/leaderboard/2041d318-dd22-47c2-a487-2818ecf14e41/c98949ae-60a8-43dc-85d7-0feb0b92e719')
            ->seeJson([
                'error' => false,
            ]);
    }
}
