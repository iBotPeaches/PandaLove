<?php namespace Onyx\Halo5;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Onyx\Account;
use Onyx\Destiny\Enums\Console;
use Onyx\Destiny\Helpers\String\Text as DestinyText;
use Onyx\Halo5\Collections\GameHistoryCollection;
use Onyx\Halo5\Helpers\String\Text as Halo5Text;
use Onyx\Halo5\Helpers\Network\Http;
use Onyx\Halo5\Helpers\String\Text;
use Onyx\Halo5\Objects\Data;
use Onyx\Halo5\Objects\Match;
use Onyx\Halo5\Objects\MatchEvent;
use Onyx\Halo5\Objects\MatchEventAssist;
use Onyx\Halo5\Objects\MatchPlayer;
use Onyx\Halo5\Objects\MatchTeam;
use Onyx\Halo5\Objects\PlaylistData;
use Onyx\Halo5\Objects\Season;
use Onyx\Halo5\Objects\Warzone;
use Ramsey\Uuid\Uuid;

class Client extends Http {

    public static $updateRan = false;
    
    const PER_PAGE = 9;

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    /**
     * @param $typeId string (warzone/arena)
     * @param $gameId uuid
     * @param bool $events
     * @return bool|mixed|Match
     * @throws \Exception
     */
    public function getGameByGameId($typeId, $gameId, $events = false)
    {
        $match = $this->checkCacheForGame($gameId, $events);

        if ($match instanceof Match)
        {
            if ($events)
            {
                if (count($match->events) > 0)
                {
                    return $match;
                }
                else
                {
                    \DB::beginTransaction();

                    try
                    {
                        $this->addMatchEvents($match);
                        \DB::commit();

                        return $this->getGameByGameId($typeId, $gameId, $events);
                    }
                    catch (\Exception $e)
                    {
                        \DB::rollBack();
                        \Cache::flush();
                        \Bugsnag::notifyException($e);

                        throw $e;
                    }
                }
            }

            $match->players->each(function($player)
            {
                $player->kd = $player->kd();
            });

            $match->players = $match->players->sortBy(function($player)
            {
                return $player->kd * 100;
            }, SORT_REGULAR, true);

            return $match;
        }
        else
        {
            switch ($typeId)
            {
                case "warzone":
                    break;

                case "arena":
                    break;

                default:
                    throw new \Exception('This is not a valid typeId.');
            }

            \DB::beginTransaction();

            try
            {
                $url = sprintf(Constants::$postgame_carnage, $typeId, $gameId);
                $json = $this->getJson($url);

                $match = $this->parseGameData($json, $gameId);
                \DB::commit();

                return $this->getGameByGameId($typeId, $match->uuid);
            }
            catch (\Exception $e)
            {
                \DB::rollBack();
                \Cache::flush();
                \Bugsnag::notifyException($e);
                throw $e;
            }
        }
    }

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

    public function getAccountsByGamertags($gamertags)
    {
        $data = $this->_getBulkArenaServiceRecord($gamertags);

        foreach ($data as $entry)
        {
            if ($entry['ResultCode'] != 0)
            {
                \Bugsnag::notifyError("Account Failed", $entry['Id'], $entry);
                throw new \Exception("This account: " . $entry['Id'] . " Could not be loaded");
            }

            try
            {
                $account = Account::firstOrCreate([
                    'gamertag' => $entry['Id'],
                    'accountType' => 1
                ]);

                $account->save();

                /** @var Data $h5_data */
                $h5_data = $account->h5;

                if (! $h5_data instanceof Data)
                {
                    $h5_data = new Data();
                    $h5_data->account_id = $account->id;
                }

                $this->_parseServiceRecord($account, $entry['Result'], $h5_data, true);
            }
            catch (QueryException $e)
            {
                \Bugsnag::notifyException($e);
                throw $e;
            }
        }
    }

    /**
     * @param $data
     * @param $gameId
     * @return Match
     */
    public function parseGameData($data, $gameId)
    {
        $match = new Match();
        $match->uuid = $gameId;
        $match->map_variant = $data['MapVariantId'];
        $match->game_variant = $data['GameVariantId'];
        $match->playlist_id = $data['PlaylistId'];
        $match->map_id = $data['MapId'];
        $match->gamebase_id = $data['GameBaseVariantId'];
        $match->season_id = $data['SeasonId'];
        $match->isTeamGame = boolval($data['IsTeamGame']);
        $match->duration = $data['TotalDuration'];
        $match->save();

        foreach ($data['TeamStats'] as $team)
        {
            $_team = new MatchTeam();
            $_team->uuid = Uuid::uuid4();
            $_team->game_id = $gameId;
            $_team->team_id = $team['TeamId'];
            $_team->score = $team['Score'];
            $_team->rank = $team['Rank'];
            $_team->round_stats = $team['RoundStats'];
            $_team->save();
        }

        $gts = '';
        foreach ($data['PlayerStats'] as $player)
        {
            $gamertag = $player['Player']['Gamertag'];

            $account = $this->checkCacheForGamertag($gamertag);

            if (! $account instanceof Account)
            {
                $gts .= Halo5Text::encodeGamertagForApi($gamertag) . ",";
            }
        }

        if ($gts != '')
        {
            $this->getAccountsByGamertags(rtrim($gts, ","));
        }

        foreach ($data['PlayerStats'] as $player)
        {
            $_player = new MatchPlayer();
            $_player->uuid = Uuid::uuid4();
            $_player->game_id = $gameId;
            $_player->killed = $player['KilledOpponentDetails'];
            $_player->killed_by = $player['KilledByOpponentDetails'];
            $_player->account_id = $this->getAccount($player['Player']['Gamertag']);
            $_player->team_id = $gameId . "_" . $player['TeamId'];
            $_player->medals = $player['MedalAwards'];
            $_player->enemies = $player['EnemyKills'];
            $_player->weapons = $player['WeaponStats'];
            $_player->impulses = $player['Impulses'];

            if (isset($player['WarzoneLevel']))
            {
                $_player->warzone_req = $player['WarzoneLevel'];
                $_player->total_pies = $player['TotalPiesEarned'];
            }

            if (isset($player['CurrentCsr']))
            {
                $_player->CsrTier = $player['CurrentCsr']['Tier'];
                $_player->CsrDesignationId = $player['CurrentCsr']['DesignationId'];
                $_player->Csr = $player['CurrentCsr']['Csr'];
                $_player->percentNext = $player['CurrentCsr']['PercentToNextTier'];
                $_player->ChampionRank = $player['CurrentCsr']['Rank'];
            }

            if (isset($player['MeasurementMatchesLeft']) && $player['MeasurementMatchesLeft'] != 0)
            {
                $_player->CsrDesignationId = 0;
                $_player->CsrTier = 10 - $player['MeasurementMatchesLeft'];
            }

            $_player->spartanRank = $player['XpInfo']['SpartanRank'];
            $_player->rank = $player['Rank'];
            $_player->dnf = boolval($player['DNF']);
            $_player->avg_lifestime = $player['AvgLifeTimeOfPlayer'];
            $_player->totalKills = $player['TotalKills'];
            $_player->totalSpartanKills = $player['TotalSpartanKills'];
            $_player->totalHeadshots = $player['TotalHeadshots'];
            $_player->totalDeaths = $player['TotalDeaths'];
            $_player->totalAssists = $player['TotalAssists'];
            $_player->totalTimePlayed = $player['TotalTimePlayed'];
            $_player->weapon_dmg = $player['TotalWeaponDamage'];
            $_player->shots_fired = $player['TotalShotsFired'];
            $_player->shots_landed = $player['TotalShotsLanded'];
            $_player->totalMeleeKills = $player['TotalMeleeKills'];
            $_player->totalAssassinations = $player['TotalAssassinations'];
            $_player->totalGroundPounds = $player['TotalGroundPoundKills'];
            $_player->totalGrenadeKills = $player['TotalGrenadeKills'];
            $_player->totalPowerWeaponKills = $player['TotalPowerWeaponKills'];
            $_player->totalPowerWeaponTime = $player['TotalPowerWeaponPossessionTime'];
            $_player->save();
        }

        return $match;
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
        catch (H5PlayerNotFoundException $ex)
        {
            \DB::rollBack();

            // If we are here then the 343 responses failed. At the same time
            // we know the account exists, because the API returned a valid response
            // but invalid value.
            // We will check another endpoint (Xbox) and if that returns false. We are
            // marking this H5 account as disabled.
            $xbox = new \Onyx\XboxLive\Client();
            try
            {
                $check = $xbox->fetchAccountBio($account);
            }
            catch (\Exception $e)
            {
                $account->h5->disabled = true;
                $account->h5->save();
                return false;
            }
        }
        catch (\Exception $ex)
        {
            \DB::rollBack();
            \Cache::flush();
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
     * @return bool
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

        return $this->_parseServiceRecord($account, $record, $h5_data);
    }

    /**
     * @param $match Match
     * @throws \Exception
     */
    public function addMatchEvents($match)
    {
        $json = $this->getEvents($match->uuid);

        if (isset($json['GameEvents']) && is_array($json['GameEvents']))
        {
            if (! $json['IsCompleteSetOfEvents'])
            {
                throw new \Exception('This game (As reported by 343) does not have a complete set of Match Event data.
                To prevent ugly looking stats, we will not process this game. Sorry');
            }

            MatchEvent::where('game_id', $match->uuid)->delete();

            foreach ($json['GameEvents'] as $event)
            {
                $killer = $event['Killer']['Gamertag'];
                $victim = $event['Victim']['Gamertag'];

                if ($killer == '' && $victim == '')
                {
                    continue;
                }

                $matchEvent = new MatchEvent();
                $matchEvent->game_id = $match->uuid;
                $matchEvent->death_owner = $event['DeathDisposition'];
                $matchEvent->death_type = $event;

                $matchEvent->killer_id = ($event['Killer']['Gamertag'] != null) ? $this->getAccount($event['Killer']['Gamertag']) : null;
                $matchEvent->killer_type = $event['KillerAgent'];
                $matchEvent->killer_attachments = $event['KillerWeaponAttachmentIds'];
                $matchEvent->killer_weapon_id = $event['KillerWeaponStockId'];
                $matchEvent->setPoint('Killer', $event['KillerWorldLocation']);

                $matchEvent->victim_id = ($event['Victim']['Gamertag'] != null) ? $this->getAccount($event['Victim']['Gamertag']) : null;
                $matchEvent->victim_type = $event['VictimAgent'];
                $matchEvent->victim_attachments = $event['VictimAttachmentIds'];
                $matchEvent->victim_stock_id = $event['VictimStockId'];
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
    public function getPlayerMatches($account, $types = 'arena,warzone', $start = 0)
    {
        if ($start != 0)
        {
            $start = (self::PER_PAGE * $start);
        }
        
        $url = sprintf(Constants::$player_matches, $account->gamertag, $types, $start, self::PER_PAGE);

        $matches = $this->getJson($url, 3); // 3 minute cache

        $games = [
            'ResultCount' => $matches['ResultCount'],
            'Results' => []
        ];

        if ($matches['ResultCount'] > 0)
        {
            $games['Results'] = new GameHistoryCollection($account, $matches['Results']);
        }

        return $games;
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

    public function getRanks()
    {
        $url = Constants::$metadata_ranks;

        return $this->getJson($url);
    }

    public function getTeams()
    {
        $url = Constants::$metadata_teams;

        return $this->getJson($url);
    }
    
    public function getEnemies()
    {
        $url = Constants::$metadata_enemies;
        
        return $this->getJson($url);
    }
    
    public function getImpulses()
    {
        $url = Constants::$metadata_impulses;
        
        return $this->getJson($url);
    }
    
    public function getVehicles()
    {
        $url = Constants::$metadata_vehicles;
        
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

        return $this->getJson($url, 2); // Cache for 2 minutes for people refreshing the error pages
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

        throw new H5PlayerNotFoundException('Game could not be loaded.');
    }

    private function _getBulkArenaServiceRecord($gamertags)
    {
        $url = sprintf(Constants::$servicerecord_arena, $gamertags);
        $json = $this->getJson($url);

        if (isset($json['Results'][0]['ResultCode']) && $json['Results'][0]['ResultCode'] == 0)
        {
            return $json['Results'];
        }

        throw new H5PlayerNotFoundException('Game could not be loaded.');
    }

    private function _getArenaServiceRecord($account)
    {
        $url = sprintf(Constants::$servicerecord_arena, Halo5Text::encodeGamertagForApi($account->gamertag));
        $json = $this->getJson($url, 2);

        if (isset($json['Results'][0]['ResultCode']) && $json['Results'][0]['ResultCode'] == 0)
        {
            return $json['Results'][0]['Result'];
        }

        throw new H5PlayerNotFoundException('Game could not be loaded.');
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

        throw new H5PlayerNotFoundException('Game could not be loaded.');
    }

    /**
     * @param Account $account
     * @param array $record
     * @param Data $h5_data
     * @return bool
     */
    private function _parseServiceRecord(&$account, $record, &$h5_data, $bulkAdded = false)
    {
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
        $h5_data->weapons = $record['ArenaStats']['WeaponStats'];
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

        PlaylistData::where('account_id', $account->id)
            ->where(function ($query) use ($record)
            {
                $query->where('seasonId', $record['ArenaStats']['ArenaPlaylistStatsSeasonId']);
                $query->orWhere('seasonId', 'IS', DB::raw('null'));
            })
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

        // We need a way to determine these additions vs others
        // so mark as -1 so the updater knows to trigger an update on these
        if ($bulkAdded)
        {
            $h5_data->inactiveCounter = 128;
        }

        $account->h5 = $h5_data;
        return $h5_data->save();
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

        if ($h5->inactiveCounter >= 128)
        {
            $h5->inactiveCounter = 0;
        }
        self::$updateRan = true;
        return $h5->save();
    }

    /**
     * @param $gamertag
     * @return \Onyx\Account|void
     */
    private function checkCacheForGamertag($gamertag)
    {
        $account = \Cache::remember('gamertag-' . $gamertag, 60, function() use ($gamertag)
        {
            return Account::where('seo', DestinyText::seoGamertag($gamertag))
                ->where('accountType', Console::Xbox)
                ->first();
        });

        if ($account instanceof Account)
        {
            return $account;
        }

        return false;
    }

    /**
     * @param $gameId
     * @param boolean $events
     * @return bool
     */
    private function checkCacheForGame($gameId, $events = false)
    {
        if ($events)
        {
            $select = ['events.assists.account', 'events.killer_weapon', 'events.victim_enemy', 'events.victim', 'events.killer.h5_emblem.account'];
        }
        else
        {
            $select = ['teams.team', 'map', 'players.account', 'players.csr', 'players.team.team', 'gametype', 'season', 'playlist'];
        }

        /* @var Match $match */
        $match = Match::with($select)
            ->where('uuid', $gameId)
            ->first();

        if ($match instanceof Match)
        {
            return $match;
        }

        return false;
    }
}

class H5PlayerNotFoundException extends \Exception {};
