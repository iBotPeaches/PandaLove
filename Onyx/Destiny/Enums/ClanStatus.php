<?php namespace Onyx\Destiny\Enums;

abstract class ClanStatus {

    /**
     * Active
     *
     * In Clan, active member
     */
    const Active = 0;

    /**
     * Inactive
     *
     * Was part of Clan, became inactive
     */
    const Inactive = 1;

    /**
     * Honorary
     *
     * Friend of a Clan member, not part of the clan
     */
    const Honorary = 2;

    /**
     * Random
     *
     * A Random LFG guy, or someone we (The Clan) don't know
     */
    const Random = 3;
}