<?php namespace Onyx\Destiny;

class Constants {

    /**
     * Location to obtain MembershipID
     * @var string
     */
    public static $searchDestinyPlayer = 'https://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/%1$s/%2$s';

    /**
     * Obtain basic information (weekly, characters, etc) about a membershipId
     * @var string
     * @url https://www.bungie.net/platform/destiny/help/HelpDetail/GET?uri=%7bmembershipType%7d%2fAccount%2f%7bdestinyMembershipId%7d%2f
     */
    public static $platformDestiny = 'https://www.bungie.net/Platform/Destiny/%1$d/Account/%2$s/Summary';

    /**
     * Obtain profile information (motto, clans, groups) about a membershipId / membershipType
     * @var string
     */
    public static $getBungieAccount = 'https://www.bungie.net/Platform/User/GetBungieNetUserById/%1$s/%2$d';

    /**
     * Obtain Post Game Carnage Report for a game (instanceId)
     * @var string
     */
    public static $postGameCarnageReport = 'https://www.bungie.net/platform/Destiny/Stats/PostGameCarnageReport/%1$s';

    /**
     * Obtain Item hashes / emblems
     * @var string
     */
    public static $explorerItems = 'https://www.bungie.net/Platform/Destiny/Manifest/InventoryItem/%1$s';

    /**
     * Obtain XUR data when present
     *
     * @var string
     */
    public static $xurData = 'https://www.bungie.net/Platform/Destiny/Advisors/Xur';

    /**
     * URL to items on guardian.gg
     *
     * @var string
     */
    public static $ggItem = 'https://guardian.gg/en/items/%d';
}