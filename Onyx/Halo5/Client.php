<?php namespace Onyx\Halo5;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text as DestinyText;
use Onyx\Halo5\Helpers\String\Text as Halo5Text;
use Onyx\Halo5\Helpers\Network\Http;
use Onyx\Halo5\Helpers\String\Text;
use Onyx\Halo5\Objects\Data;
use Onyx\Halo5\Objects\Match;
use Onyx\Halo5\Objects\MatchEvent;
use Onyx\Halo5\Objects\MatchEventAssist;
use Onyx\Halo5\Objects\PlaylistData;
use Onyx\Halo5\Objects\Season;
use Onyx\Halo5\Objects\Warzone;

class Client extends Http {

    public static $updateRan = false;

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    /**
     * @param $gamertag
     * @return Account|void|static
     * @throws H5PlayerNotFoundException
     * @throws Helpers\Network\ThreeFourThreeOfflineException
     */
    public function getAccountByGamertag($gamertag)
    {
        $url = sprintf(Constants::$servicerecord_arena, Text::encodeGamertagForApi($gamertag));

        $account = $this->checkCacheForGamertag($gamertag);

        if ($account instanceof Account)
        {
            return $account;
        }

        $json = $this->getJson($url, 2);

        if (isset($json['Results'][0]['ResultCode']) && $json['Results'][0]['ResultCode'] == 0) // @todo this check is wrong.
        {
            try
            {
                return Account::firstOrCreate([
                    'gamertag' => $json['Results'][0]['Id']
                ]);
            }
            catch (QueryException $e)
            {
                throw new H5PlayerNotFoundException();
            }
        }
        else
        {
            throw new H5PlayerNotFoundException();
        }
    }

    /**
     * @param $account Account
     */
    public function updateH5Account($account)
    {
        \DB::beginTransaction();

        try
        {
            $this->pullArenaSeasonHistoryRecord($account);
            $this->updateArenaServiceRecord($account);
            $this->updateWarzoneServiceRecord($account);
            $this->updateSpartan($account);
            $this->updateEmblem($account);

            unset($account->h5);
            $account->touch();
            $account->save();

            \DB::commit();
        }
        catch (\Exception $ex)
        {
            \DB::rollBack();
        }
    }

    /**
     * @param $account Account
     * @param int $size
     */
    public function updateEmblem(&$account, $size = 256)
    {
        $emblem = $this->_getEmblemImage($account, $size);

        if ($emblem == null)
            return;

        $base = 'uploads/h5/';

        // Create directory
        if (! File::isDirectory(public_path($base . $account->seo)))
        {
            File::makeDirectory(public_path($base . $account->seo), 0755, true);
        }

        $emblem->save(public_path($base . $account->seo . "/" . 'emblem.png'));
    }

    /**
     * @param $account Account
     * @param int $size
     */
    public function updateSpartan(&$account, $size = 512)
    {
        $spartan = $this->_getSpartanImage($account, $size);

        if ($spartan == null)
            return;

        $base = 'uploads/h5/';

        // Create directory
        if (! File::isDirectory(public_path($base . $account->seo)))
        {
            File::makeDirectory(public_path($base . $account->seo), 0755, true);
        }

        $spartan->save(public_path($base . $account->seo . "/" . 'spartan.png'));
    }

    /**
     * @param $account Account
     */
    public function pullArenaSeasonHistoryRecord(&$account)
    {
        $seasons = Season::all();

        /** @var $season Season */
        foreach ($seasons as $season)
        {
            // check if season is in past and exists, if so don't reload
            $playlist = PlaylistData::where('account_id', $account->id)
                ->where('seasonId', $season->contentId)
                ->where('updated_at', '>=', $season->end_date)
                ->first();

            if ($playlist == null && ! $season->isFuture())
            {
                $this->updateArenaServiceRecord($account, $season->contentId);
            }
        }
    }

    /**
     * @param $account Account
     */
    public function updateWarzoneServiceRecord(&$account)
    {
        $h5_warzone = $account->h5->warzone;

        if (! $h5_warzone instanceof Warzone)
        {
            $h5_warzone = new Warzone();
            $h5_warzone->account_id = $account->id;
        }

        $record = $this->_getWarzoneServiceRecord($account);

        $h5_warzone->totalKills = $record['WarzoneStat']['TotalSpartanKills'];
        $h5_warzone->totalHeadshots = $record['WarzoneStat']['TotalHeadshots'];
        $h5_warzone->totalDeaths = $record['WarzoneStat']['TotalDeaths'];
        $h5_warzone->totalAssists = $record['WarzoneStat']['TotalAssists'];

        $h5_warzone->totalGames = $record['WarzoneStat']['TotalGamesCompleted'];
        $h5_warzone->totalGamesWon = $record['WarzoneStat']['TotalGamesWon'];
        $h5_warzone->totalGamesLost = $record['WarzoneStat']['TotalGamesLost'];
        $h5_warzone->totalGamesTied = $record['WarzoneStat']['TotalGamesTied'];
        $h5_warzone->totalTimePlayed = $record['WarzoneStat']['TotalTimePlayed'];

        $h5_warzone->totalPiesEarned = $record['WarzoneStat']['TotalPiesEarned'];

        $h5_warzone->medals = $record['WarzoneStat']['MedalAwards'];
        $h5_warzone->weapons = $record['WarzoneStat']['WeaponStats'];

        $h5_warzone->save();
    }

    /**
     * @param $account Account
     * @param null $seasonId
     */
    public function updateArenaServiceRecord(&$account, $seasonId = null)
    {
        /** @var Data $h5_data */
        $h5_data = $account->h5;

        if (! $h5_data instanceof Data)
        {
            $h5_data = new Data();
            $h5_data->account_id = $account->id;
        }

        if ($seasonId != null)
        {
            $record = $this->_getArenaServiceRecordSeason($account, $seasonId);
            $this->_checkForStatChange($h5_data, $h5_data->Xp, $record['Xp']);
        }
        else
        {
            $record = $this->_getArenaServiceRecord($account);
            $this->_checkForStatChange($h5_data, $h5_data->Xp, $record['Xp']);
        }

        // dump the stats
        $h5_data->totalKills = $record['ArenaStats']['TotalKills'];
        $h5_data->totalSpartanKills = $record['ArenaStats']['TotalSpartanKills'];
        $h5_data->totalHeadshots = $record['ArenaStats']['TotalHeadshots'];
        $h5_data->totalDeaths = $record['ArenaStats']['TotalDeaths'];
        $h5_data->totalAssists = $record['ArenaStats']['TotalAssists'];

        $h5_data->totalGames = $record['ArenaStats']['TotalGamesCompleted'];
        $h5_data->totalGamesWon = $record['ArenaStats']['TotalGamesWon'];
        $h5_data->totalGamesLost = $record['ArenaStats']['TotalGamesLost'];
        $h5_data->totalGamesTied = $record['ArenaStats']['TotalGamesTied'];
        $h5_data->totalTimePlayed = $record['ArenaStats']['TotalTimePlayed'];

        $h5_data->spartanRank = $record['SpartanRank'];
        $h5_data->Xp = $record['Xp'];

        $h5_data->medals = $record['ArenaStats']['MedalAwards'];
        $h5_data->seasonId = $record['ArenaStats']['ArenaPlaylistStatsSeasonId'];

        if ($record['ArenaStats']['HighestCsrAttained'] != null)
        {
            $h5_data->highest_CsrTier = $record['ArenaStats']['HighestCsrAttained']['Tier'];
            $h5_data->highest_CsrDesignationId = $record['ArenaStats']['HighestCsrAttained']['DesignationId'];
            $h5_data->highest_Csr = $record['ArenaStats']['HighestCsrAttained']['Csr'];
            $h5_data->highest_percentNext = $record['ArenaStats']['HighestCsrAttained']['PercentToNextTier'];
            $h5_data->highest_rank = $record['ArenaStats']['HighestCsrAttained']['Rank'];
            $h5_data->highest_CsrPlaylistId = $record['ArenaStats']['HighestCsrPlaylistId'];
            $h5_data->highest_CsrSeasonId = $record['ArenaStats']['HighestCsrSeasonId'];
        }

        // clear out old playlist history, dump new playlists in that seasonId or null
        PlaylistData::where('account_id', $account->id)
            ->where('seasonId', $record['ArenaStats']['ArenaPlaylistStatsSeasonId'])
            ->orWhere('seasonId', 'IS', DB::raw('null'))
            ->delete();

        foreach ($record['ArenaStats']['ArenaPlaylistStats'] as $playlist)
        {
            $p = new PlaylistData();
            $p->account_id = $account->id;
            $p->playlistId = $playlist['PlaylistId'];
            $p->measurementMatchesLeft = $playlist['MeasurementMatchesLeft'];

            // highest csr
            if ($playlist['HighestCsr'] != null)
            {
                $p->highest_CsrTier = $playlist['HighestCsr']['Tier'];
                $p->highest_CsrDesignationId = $playlist['HighestCsr']['DesignationId'];
                $p->highest_Csr = $playlist['HighestCsr']['Csr'];
                $p->highest_percentNext = $playlist['HighestCsr']['PercentToNextTier'];
                $p->highest_rank = $playlist['HighestCsr']['Rank'];
            }

            // current csr
            if ($playlist['Csr'] != null)
            {
                $p->current_CsrTier = $playlist['Csr']['Tier'];
                $p->current_CsrDesignationId = $playlist['Csr']['DesignationId'];
                $p->current_Csr = $playlist['Csr']['Csr'];
                $p->current_percentNext = $playlist['Csr']['PercentToNextTier'];
                $p->current_rank = $playlist['Csr']['Rank'];
            }

            $p->totalKills = $playlist['TotalKills'];
            $p->totalSpartanKills = $playlist['TotalSpartanKills'];
            $p->totalHeadshots = $playlist['TotalHeadshots'];
            $p->totalDeaths = $playlist['TotalDeaths'];
            $p->totalAssists = $playlist['TotalAssists'];

            $p->totalGames = $playlist['TotalGamesCompleted'];
            $p->totalGamesWon = $playlist['TotalGamesWon'];
            $p->totalGamesLost = $playlist['TotalGamesLost'];
            $p->totalGamesTied = $playlist['TotalGamesTied'];
            $p->totalTimePlayed = $playlist['TotalTimePlayed'];

            $p->seasonId = $record['ArenaStats']['ArenaPlaylistStatsSeasonId'];
            $p->save();
        }

        $account->h5 = $h5_data;
        $h5_data->save();
    }

    public function addMatchEvents($matchId)
    {
        $json = $this->getEvents($matchId);

        if (isset($json['GameEvents']) && is_array($json['GameEvents']))
        {
            if (! $json['IsCompleteSetOfEvents'])
            {
                throw new \Exception('This game (As reported by Bungie) does not have a complete set of Match Event data.
                To prevent ugly looking stats, we will not process this game. Sorry');
            }

            try
            {
                $game = Match::where('uuid', $matchId)->firstOrFail();
            }
            catch (ModelNotFoundException $e)
            {
                $game = new Match();
                $game->uuid = $matchId;
                $game->save();
            }

            foreach ($json['GameEvents'] as $event)
            {
                $matchEvent = new MatchEvent();
                $matchEvent->game_id = $game->uuid;
                $matchEvent->death_owner = $event['DeathDisposition'];
                $matchEvent->death_type = $event;

                $matchEvent->killer = $this->getAccount($event['Killer']['Gamertag']);
                $matchEvent->killer_type = $event['KillerAgent'];
                $matchEvent->killer_attachments = $event['KillerWeaponAttachmentIds'];
                $matchEvent->killer_weapon = $event['KillerWeaponStockId'];
                $matchEvent->setPoint('Killer', $event['KillerWorldLocation']);

                $matchEvent->victim = $this->getAccount($event['Victim']['Gamertag']);
                $matchEvent->victim_type = $event['VictimAgent'];
                $matchEvent->victim_attachments = $event['VictimAttachmentIds'];
                $matchEvent->victim_weapon = $event['VictimStockId'];
                $matchEvent->setPoint('Victim', $event['VictimWorldLocation']);

                $matchEvent->event_name = $event['EventName'];
                $matchEvent->seconds_since_start = $event['TimeSinceStart'];

                $matchEvent->save();

                if (is_array($event['Assistants']))
                {
                    foreach($event['Assistants'] as $assistant)
                    {
                        $assist = new MatchEventAssist();
                        $assist->match_event = $matchEvent->uuid;
                        $assist->account_id = $this->getAccount($assistant['Gamertag']);
                        $assist->save();
                    }
                }
            }
        }
        else
        {
            throw new \Exception('Match Event not found.');
        }
    }

    /**
     * @param Account $account
     * @param string $types - comma delimited list of game types
     * @param int $start - start
     * @return array
     * @throws Helpers\Network\ThreeFourThreeOfflineException
     */
    public function getPlayerMatches($account, $types = '', $start = 0)
    {
        $url = sprintf(Constants::$player_matches, $account->gamertag, $types, $start, 25);

        return $this->getJson($url);
    }

    public function getMedals()
    {
        $url = Constants::$metadata_medals;

        return $this->getJson($url);
    }

    public function getPlaylists()
    {
        $url = Constants::$metadata_playlist;

        return $this->getJson($url);
    }

    public function getSeasons()
    {
        $url = Constants::$metadata_seasons;

        return $this->getJson($url);
    }

    public function getWeapons()
    {
        $url = Constants::$metadata_weapons;

        return $this->getJson($url);
    }

    public function getGametypes()
    {
        $url = Constants::$metadata_gametypes;

        return $this->getJson($url);
    }

    public function getMaps()
    {
        $url = Constants::$metadata_maps;

        return $this->getJson($url);
    }

    public function getCsrs()
    {
        $url = Constants::$metadata_csr;

        return $this->getJson($url);
    }

    /**
     * @param $matchId
     * @return array
     * @throws Helpers\Network\ThreeFourThreeOfflineException
     */
    public function getEvents($matchId)
    {
        $url = sprintf(Constants::$match_events, $matchId);

        return $this->getJson($url);
    }

    /**
     * @param $gamertag
     * @return Account|void
     */
    public function getAccount($gamertag)
    {
        $account = $this->checkCacheForGamertag($gamertag);

        if (! $account instanceof Account)
        {
            return Account::firstOrCreate([
                'gamertag' => $gamertag,
                'accountType' => 1
            ]);
        }

        return $account;
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    private function _getEmblemImage($account, $size = 256)
    {
        $url = sprintf(Constants::$emblem_image, Halo5Text::encodeGamertagForApi($account->gamertag), $size);
        return $this->getAsset($url);
    }

    private function _getSpartanImage($account, $size = 512)
    {
        $url = sprintf(Constants::$spartan_image, Halo5Text::encodeGamertagForApi($account->gamertag), $size);
        return $this->getAsset($url);
    }

    private function _getWarzoneServiceRecord($account)
    {
        $url = sprintf(Constants::$servicerecord_warzone, Halo5Text::encodeGamertagForApi($account->gamertag));
        $json = $this->getJson($url);

        if (isset($json['Results'][0]['ResultCode']) && $json['Results'][0]['ResultCode'] == 0)
        {
            return $json['Results'][0]['Result'];
        }
    }

    private function _getArenaServiceRecord($account)
    {
        $url = sprintf(Constants::$servicerecord_arena, Halo5Text::encodeGamertagForApi($account->gamertag));
        $json = $this->getJson($url, 2);

        if (isset($json['Results'][0]['ResultCode']) && $json['Results'][0]['ResultCode'] == 0)
        {
            return $json['Results'][0]['Result'];
        }
    }

    private function _getArenaServiceRecordSeason($account, $seasonId)
    {
        $url = sprintf(Constants::$servicerecord_arena, Halo5Text::encodeGamertagForApi($account->gamertag));
        $url .= "&seasonId=" . $seasonId;

        $json = $this->getJson($url);

        if (isset($json['Results'][0]['ResultCode']) && $json['Results'][0]['ResultCode'] == 0)
        {
            return $json['Results'][0]['Result'];
        }
    }

    /**
     * @param $h5 Data
     * @param $old_xp int
     * @param $new_xp int
     * @return bool
     */
    private function _checkForStatChange(&$h5, $old_xp, $new_xp)
    {
        if (self::$updateRan || $old_xp == null) return true;

        $h5->inactiveCounter = ($old_xp != $new_xp) ? 0 : $h5->inactiveCounter++;
        self::$updateRan = true;
        return $h5->save();
    }

    /**
     * @param $gamertag
     * @return \Onyx\Account|void
     */
    private function checkCacheForGamertag($gamertag)
    {
        $account = Account::where('seo', DestinyText::seoGamertag($gamertag))->first();

        if ($account instanceof Account)
        {
            return $account;
        }

        return false;
    }
}

class H5PlayerNotFoundException extends \Exception {};
