<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Onyx\XboxLive\Helpers\Network\XboxAPI;
use Onyx\XboxLive\Constants;

class UuidGenerationTest extends TestCase
{
    public function testGeneration()
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid4();
        
        $this->assertTrue($uuid instanceof \Ramsey\Uuid\Uuid);
        $this->assertTrue(strlen($uuid->toString()) > 1);
    }
}
