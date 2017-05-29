<?php


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
