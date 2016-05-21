<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Onyx\XboxLive\Helpers\Network\XboxAPI;
use Onyx\XboxLive\Constants;

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

        $this->assertEquals(array(
            'xuid' => $this->xuid
        ), $xuid);
    }
}
