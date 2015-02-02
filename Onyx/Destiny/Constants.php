<?php namespace Onyx\Destiny;

class Constants {

    /**
     * Location to obtain MembershipID
     * @var string
     */
    public static $searchDestinyPlayer = 'http://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/%1$d/%2$s';

    /**
     * Obtain basic information (weekly, characters, etc) about a membershipId
     * @var string
     */
    public static $platformDestiny = 'http://www.bungie.net/Platform/Destiny/%1$d/Account/%2$s';
}