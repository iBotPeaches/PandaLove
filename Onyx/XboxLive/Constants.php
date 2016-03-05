<?php namespace Onyx\XboxLive;

class Constants {

    /**
     * Location to obtain XUID
     * @var string
     */
    public static $getGamertagXUID = 'https://xboxapi.com/v2/xuid/%1$s';

    /**
     * Base URL for XboxAPI
     * @var string
     */
    public static $getBaseXboxAPI = 'https://xboxapi.com';

    /**
     * URL to append for account presence
     * @var string
     */
    public static $getPresenceUrl = 'https://xboxapi.com/v2/%s/presence';

    /**
     * URL to get account gamercard, including BIO for verification
     * @var string
     */
    public static $getGamercard = 'https://xboxapi.com/v2/%s/gamercard';
}