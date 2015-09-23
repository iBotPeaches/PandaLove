<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class basicPageLoadingTest extends TestCase
{

    public function testHome()
    {
        $this->visit('/')
            ->see('Panda Love');
    }

    public function testAddingUser()
    {
        $user = factory(Onyx\User::class)->create();

        $this->actingAs($user)
            ->visit('/usercp')
            ->see($user->name);
    }
}
