<?php

namespace Onyx\Fortnite;

/**
 * Class Constants.
 */
class Constants
{
    public static $oAuthToken = 'https://account-public-service-prod03.ol.epicgames.com/account/api/oauth/token';

    public static $oAuthExchange = 'https://account-public-service-prod03.ol.epicgames.com/account/api/oauth/exchange';

    public static $oAuthVerify = 'https://account-public-service-prod03.ol.epicgames.com/account/api/oauth/verify?includePerms=true';

    public static $lookup = 'https://persona-public-service-prod06.ol.epicgames.com/persona/api/public/account/lookup?q=%s';

    public static $PvE = 'https://fortnite-public-service-prod11.ol.epicgames.com/fortnite/api/game/v2/profile/%d/client/QueryProfile?profileId=athena&rvn=-1';

    public static $PvP = 'https://fortnite-public-service-prod11.ol.epicgames.com/fortnite/api/stats/accountId/%s/bulk/window/alltime';
}
