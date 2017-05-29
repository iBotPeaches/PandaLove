<?php

namespace Onyx\Halo5\Enums;

abstract class VictimAgent
{
    /**
     * Nothing.
     */
    const None = 0;

    /**
     * Another player.
     */
    const Player = 1;

    /**
     * AI, most likely in Warzone.
     */
    const AI = 2;
}
