<?php

namespace Onyx\Overwatch;

use Carbon\Carbon;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text as DestinyText;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Overwatch\Constants as OverwatchConstants;
use Onyx\Overwatch\Helpers\Game\Season;
use Onyx\Overwatch\Helpers\Network\Http;
use Onyx\Overwatch\Helpers\Network\OWApiNetworkException;
use Onyx\Overwatch\Objects\Character;
use Onyx\Overwatch\Objects\Stats;
use Onyx\XboxLive\Enums\Console;

/**
 * Class Client.
 */
class Client extends Http
{
    /**
     * @var array
     */
    private $account_cached = [];

    /**
     * @param Account $account
     * @param int     $platform
     *
     * @throws OWApiNetworkException
     *
     * @return mixed
     */
    public function fetchBlobStat($account, $platform = Console::Xbox)
    {
        // xbl/pc/psn
        $url = sprintf(OverwatchConstants::$getBlobStats, Text::blizzard($account->gamertag), Console::getOverwatchTag($platform));

        $data = $this->getJson($url);

        $types = ['any', 'us', 'eu'];
        foreach ($types as $type) {
            if (isset($data[$type]) && isset($data[$type]['stats']['competitive'])) {
                return $data[$type];
            }
        }

        throw new OWApiNetworkException('Could not find account (Either no competitive data or does not exist).');
    }

    /**
     * @param Account $account
     *
     * @return bool
     */
    public function updateAccount($account)
    {
        $data = $this->fetchBlobStat($account, $account->accountType);

        return $this->updateOrInsertStats($account, $data);
    }

    /**
     * @param $gamertag
     * @param string $platform
     *
     * @return Account
     */
    public function getAccountByTag($gamertag, $platform = 'xbl')
    {
        /** @var Account $account */
        $account = $this->checkCacheForTag($gamertag, $platform);

        if ($account !== null && $account->overwatch !== null) {
            return $account;
        }

        // Account does not exist. Make it.
        if ($account === null) {
            $account = Account::firstOrCreate([
                'gamertag'    => Text::blizzardSeo($gamertag),
                'accountType' => $platform,
            ]);
        }

        $account->gamertag = Text::blizzard($gamertag);
        $data = $this->fetchBlobStat($account, $platform);

        // Insert data
        $this->updateOrInsertStats($account, $data);

        // check for mismatch GT
        if ($account->gamertag !== $gamertag) {
            $account->gamertag = $gamertag;
            $account->save();
        }

        return $account;
    }

    /**
     * @param $account
     * @param int $platform
     *
     * @return mixed
     */
    public function checkCacheForTag($account, $platform = Console::Xbox)
    {
        $seo = DestinyText::blizzardSeo($account);

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
    }

    /**
     * @param string $char
     *
     * @return mixed
     */
    public function getMostPlaytimeChar(string $char)
    {
        return Character::where('character', $char)
            ->orderBy('playtime', 'desc')
            ->take(1)
            ->first();
    }

    //---------------------------------------------------------------------------------
    // Private Functions
    //---------------------------------------------------------------------------------

    /**
     * @param Account $account
     * @param array   $data
     *
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
                'season'     => $season['season'],
                'account_id' => $account->id,
            ]);
        }

        $oldDmg = $stats->damage_done;
        $oldHealing = $stats->healing_done;

        // dump stats
        $categories = ['average_stats', 'overall_stats', 'game_stats'];

        foreach ($categories as $category) {
            foreach ($data['stats']['competitive'][$category] as $key => $value) {
                if (\Schema::hasColumn('overwatch_stats', $key)) {
                    $stats->$key = $value;
                } else {
                    // Overwatch API is not a real API, its a scraped div API, so column names can change
                    // This reports additional columns so we know when new data was added.
                    if (!starts_with($key, 'overwatchguid')) {
                        \Log::warning('[OW] '.$account->gamertag.' had a new column: '.$key);
                    }
                }
            }
        }

        // Check if stats changed
        if ($oldDmg != $stats->damage_done || $oldHealing != $stats->healing_done) {
            $stats->inactive_counter = 0;
        } else {
            $stats->inactive_counter++;
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
     * @param Stats  $stats
     * @param string $char
     * @param array  $data
     *
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
                'character'  => $char,
            ]);
        }

        // dump stats
        $character->playtime = $data['heroes']['playtime']['competitive'][$char];
        $character->data = $data['heroes']['stats']['competitive'][$char];

        return $character->saveOrFail();
    }
}
