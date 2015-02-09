<?php namespace Onyx\Destiny;

class Constants {

    /**
     * Location to obtain MembershipID
     * @var string
     */
    public static $searchDestinyPlayer = 'https://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/%1$d/%2$s';

    /**
     * Obtain basic information (weekly, characters, etc) about a membershipId
     * @var string
     * @url https://www.bungie.net/platform/destiny/help/HelpDetail/GET?uri=%7bmembershipType%7d%2fAccount%2f%7bdestinyMembershipId%7d%2f
     */
    public static $platformDestiny = 'https://www.bungie.net/Platform/Destiny/%1$d/Account/%2$s';

    /**
     * Obtain profile information (motto, clans, groups) about a membershipId / membershipType
     * @var string
     */
    public static $getBungieAccount = 'https://www.bungie.net/Platform/User/GetBungieAccount/%1$s/%2$d';

    /**
     * Obtain Post Game Carnage Report for a game (instanceId)
     * @var string
     */
    public static $postGameCarnageReport = 'https://www.bungie.net/platform/Destiny/Stats/PostGameCarnageReport/%1$s';

    /**
     * Obtain Item hashes / emblems
     * @var string
     */
    public static $explorerItems = 'https://www.bungie.net/platform/Destiny/Explorer/Items/?count=%1$d&bucket=%2$s';
}