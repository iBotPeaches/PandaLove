<?php

namespace PandaLove\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Socialite\Two\User;

class GoogleLoggedIn extends Event
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param \Laravel\Socialite\Two\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
