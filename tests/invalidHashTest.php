<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class invalidHashTest extends TestCase
{
    public function testInvalidHash()
    {
        $invalid = 132091039130123;

        $translator = new \Onyx\Destiny\Helpers\String\Hashes();

        $item = $translator->map($invalid, false);

        $this->assertSame('Classified', $item->title);
    }
}
