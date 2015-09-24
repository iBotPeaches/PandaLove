<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class testInvalidHash extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testInvalidHash()
    {
        $invalid = 132091039130123;

        $translator = new \Onyx\Destiny\Helpers\String\Hashes();

        $item = $translator->map($invalid);

        $this->assertSame('Classified', $item->title);
    }
}
