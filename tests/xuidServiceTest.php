<?php


use Onyx\XboxLive\Constants;
use Onyx\XboxLive\Helpers\Network\XboxAPI;

class xuidServiceTest extends TestCase
{
    public $seo = 'ibot';
    public $xuid = '2533274960246448';

    public function testJsonEndpoint()
    {
        $url = sprintf(Constants::$getGamertagXUID, $this->seo);

        $xbox = new XboxAPI();
        $xuid = $xbox->getJson($url, true);

        $this->assertEquals($this->xuid, $xuid);
    }

    public function testNonJsonCastEndpoint()
    {
        $url = sprintf(Constants::$getGamertagXUID, $this->seo);

        $xbox = new XboxAPI();
        $xuid = $xbox->getJson($url, false);

        $this->assertEquals([
            'xuid' => $this->xuid,
        ], $xuid);
    }
}
