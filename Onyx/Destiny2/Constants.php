<?php

namespace Onyx\Destiny2;

class Constants
{
    /**
     * Location to obtain MembershipID.
     *
     * @var string
     */
    public static $searchDestinyPlayer = 'https://www.bungie.net/Platform/Destiny2/SearchDestinyPlayer/all/%s';

    /**
     * Obtain basic information (weekly, characters, etc) about a membershipId.
     *
     * @var string
     */
    public static $platformDestiny = 'https://www.bungie.net/Platform/Destiny2/%1$d/Profile/%2$s?lc=en&components=100,103,200,205';

    /**
     * Get information about a singlular entity.
     *
     * @var string
     */
    public static $entityDefinition = 'https://www.bungie.net/Platform/Destiny2/Manifest/%1$s/%2$s';
}
