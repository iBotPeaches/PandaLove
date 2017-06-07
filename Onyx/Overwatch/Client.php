<?php

namespace Onyx\Overwatch;

use Carbon\Carbon;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text as DestinyText;
use Onyx\Overwatch\Constants as OverwatchConstants;
use Onyx\Overwatch\Helpers\Game\Season;
use Onyx\Overwatch\Helpers\Network\Http;
use Onyx\Overwatch\Helpers\Network\OWApiNetworkException;
use Onyx\Overwatch\Objects\Character;
use Onyx\Overwatch\Objects\Stats;
use Onyx\XboxLive\Enums\Console;

/**
 * Class Client
 * @package Onyx\Overwatch
 */
class Client extends Http
{
    /**
     * @var array
     */
    private $account_cached = [];

    /**
     * @param $account
     * @param $platform
     *
     * @throws OWApiNetworkException
     *
     * @return mixed
     */
    public function fetchBlobStat($account, $platform = Console::Xbox)
    {
        // xbl/pc/psn
        $url = sprintf(OverwatchConstants::$getBlobStats, $account->gamertag, Console::getOverwatchTag($platform));

        $data = $this->getJson($url);

        if (isset($data['any'])) {
            return $data['any'];
        }

        throw new OWApiNetworkException('Could not find account.');
    }

    public function getAccountByTag($account, $platform = 'xbl')
    {
        /** @var Account $account */
        $account = $this->checkCacheForTag($account, $platform);

        if ($account !== null && $account->overwatch->first() !== null) {
            return $account;
        }

        // Account does not exist. Make it.
        if ($account === null) {
            $account = Account::firstOrCreate([
                'gamertag'    => $account,
                'accountType' => $platform,
            ]);
        }

        $data = $this->fetchBlobStat($account, $platform);

        // Insert data
        $this->updateOrInsertStats($account, $data);

        return $account;
    }

    /**
     * @param $account
     * @param int $platform
     * @return mixed
     */
    public function checkCacheForTag($account, $platform = Console::Xbox)
    {
        $seo = DestinyText::seoGamertag($account);

        if (isset($this->account_cached[$seo])) {
            return $this->account_cached[$seo];
        }

        $account = Account::where('seo', $seo)
            ->with('overwatch')
            ->where('accountType', $platform)
            ->first();

        if ($account !== null) {
            $this->account_cached[$seo] = $account;
            return $account;
        }

        return null;
    }

    //---------------------------------------------------------------------------------
    // Private Functions
    //---------------------------------------------------------------------------------

    /**
     * @param Account $account
     * @param array $data
     * @return bool
     */
    private function updateOrInsertStats($account, $data)
    {
        $season = Season::getSeason(new Carbon('now', 'UTC'));

        /** @var Stats $stats */
        $stats = Stats::where('season', $season['season'])
            ->where('account_id', $account->id)
            ->first();

        if ($stats === null) {
            $stats = Stats::firstOrCreate([
                'season' => $season['season'],
                'account_id' => $account->id
            ]);
        }

        // dump stats
        $categories = ['average_stats', 'overall_stats', 'game_stats'];

        foreach ($categories as $category) {
            foreach ($data['stats']['competitive'][$category] as $key => $value) {
                $stats->$key = $value;
            }
        }

        // dump characters
        foreach ($data['heroes']['playtime']['competitive'] as $character => $playtime) {
            if ($playtime > 0) {
                $this->updateOrInsertCharacterStats($stats, $character, $data);
            }
        }

        return $stats->saveOrFail();
    }

    /**
     * @param Stats $stats
     * @param string $char
     * @param array $data
     * @return bool
     */
    private function updateOrInsertCharacterStats($stats, $char, $data)
    {
        /** @var Character $character */
        $character = Character::where('account_id', $stats->id)
            ->where('character', $char)
            ->first();

        if ($character === null) {
            $character = Character::firstOrCreate([
                'account_id' => $stats->id,
                'character' => $char
            ]);
        }

        // dump stats
        $character->playtime = $data['heroes']['playtime']['competitive'][$char];
        $character->data = $data['heroes']['stats']['competitive'][$char];

        return $character->saveOrFail();
    }
}
