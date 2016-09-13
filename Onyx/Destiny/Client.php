<?php namespace Onyx\Destiny;

use Carbon\Carbon;
use GuzzleHttp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Onyx\Account;
use Onyx\Destiny\Enums\Console;
use Onyx\Destiny\Helpers\Network\Http;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Helpers\Utils\Gametype;
use Onyx\Destiny\Objects\Character;
use Onyx\Destiny\Objects\Data;
use Onyx\Destiny\Objects\Game;
use Onyx\Destiny\Objects\GamePlayer;
use Onyx\Destiny\Objects\PVP;
use PandaLove\Commands\UpdateGamertag;

class Client extends Http {

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    /**
     * @param $instanceId
     * @param $type (Raid, Flawless, PVP)
     * @return mixed
     * @throws GameNotFoundException
     * @throws Helpers\Network\BungieOfflineException
     */
    public function fetchGameByInstanceId($instanceId, $type = null)
    {
        $url = sprintf(Constants::$postGameCarnageReport, $instanceId);

        try
        {
            $game = Game::where('instanceId', $instanceId)->firstOrFail();
            return $game;
        }
        catch (ModelNotFoundException $e)
        {
            $json = $this->getJson($url);

            if (isset($json['Response']['data']['activityDetails']))
            {
                $this->createGame($url, $json, $type);
            }
            else
            {
                throw new GameNotFoundException();
            }
        }
    }

    /**
     * @param string $instanceId
     * @param string $type
     * @param int $raidTuesday
     * @throws GameNotFoundException
     */
    public function updateTypeOfGame($instanceId, $type, $raidTuesday = null)
    {
        try
        {
            $game = Game::where('instanceId', $instanceId)->firstOrFail();
            $game->type = $type;

            if ($raidTuesday != null && $game->type == "Raid")
            {
                $game->raidTuesday = intval($raidTuesday);
            }

            if ($raidTuesday != null && $game->type == "ToO")
            {
                // Trials Of Osiris
                $game->passageId = intval($raidTuesday);
            }

            $game->save();

            return $game;
        }
        catch (ModelNotFoundException $e)
        {
            throw new GameNotFoundException();
        }
    }

    /**
     * @param $instanceId
     * @return bool
     * @throws Helpers\Network\BungieOfflineException
     */
    public function updateGame($instanceId)
    {
        try
        {
            $game = Game::where('instanceId', $instanceId)->firstOrFail();
            $url = sprintf(Constants::$postGameCarnageReport, $instanceId);
            $json = $this->getJson($url);

            $this->updateGameForNewField($json, $game);
        }
        catch (ModelNotFoundException $e)
        {
            return false;
        }
    }

    /**
     * @param $username string
     * @return bool|array
     * @throws Helpers\Network\BungieOfflineException
     * @throws PlayerNotFoundException
     */
    public function searchAccountByName($username)
    {
        $platform = "all";
        $url = sprintf(Constants::$searchDestinyPlayer, $platform, trim($username));

        $json = $this->getJson($url, 60 * 24);

        $accounts = [];
        if (isset($json['Response']) && count($json['Response']) >= 1)
        {
            foreach($json['Response'] as $item)
            {
                $cache = $this->checkCacheForGamertagByConsole($item['membershipType'], $item['displayName']);

                if ($cache == false)
                {
                    $cache = Account::firstOrCreate([
                        'gamertag' => $item['displayName'],
                        'accountType' => $item['membershipType'],
                    ]);

                    try
                    {
                        $data = Data::firstOrCreate([
                            'account_id' => $cache->id,
                            'membershipId' => $item['membershipId']
                        ]);
                    }
                    catch (\Exception $e)
                    {
                        /** @var Data $data */
                        $data = Data::firstOrCreate([
                            'membershipId' => $item['membershipId']
                        ]);

                        $data->account_id = $cache->id;
                        $data->save();
                    }
                }

                $accounts[] = $cache;
            }

            return $accounts;
        }

        throw new PlayerNotFoundException();
    }

    /**
     * @param $platform
     * @param $gamertag
     * @return \Onyx\Account
     * @throws Helpers\Network\BungieOfflineException
     * @throws PlayerNotFoundException
     */
    public function fetchAccountByGamertag($platform, $gamertag)
    {
        $platform = intval($platform);
        $url = sprintf(Constants::$searchDestinyPlayer, $platform, trim($gamertag));

        $account = $this->checkCacheForGamertagByConsole($platform, $gamertag);

        if ($account instanceof Account)
        {
            return $account;
        }

        $json = $this->getJson($url);

        if (isset($json['Response'][0]['membershipId']))
        {
            $account = Account::firstOrCreate([
                'gamertag' => $json['Response'][0]['displayName'],
                'accountType' => $json['Response'][0]['membershipType'],
            ]);

            $data = new Data();
            $data->account_id = $account->id;
            $data->membershipId = $json['Response'][0]['membershipId'];
            $data->save();

            return $account;
        }
        else
        {
            throw new PlayerNotFoundException();
        }
    }

    /**
     * @param /Onyx/Destiny/Objects/Data $account
     * @return array
     * @throws Helpers\Network\BungieOfflineException
     */
    public function fetchAccountData($account)
    {
        $url = sprintf(Constants::$platformDestiny, $account->accountType, $account->destiny->membershipId);

        $data = $account->destiny;
        $json = $this->getJson($url);

        if (isset($json['Response']['data']['clanName']))
        {
            $data->clanName = $json['Response']['data']['clanName'];

            if (isset($json['Response']['data']['clanTag']))
            {
                $data->clanTag = $json['Response']['data']['clanTag'];
            }
        }

        if (isset($json['Response']['data']['inventory']['currencies']))
        {
            $data->glimmer = $json['Response']['data']['inventory']['currencies'][0]['value'];
            $data->legendary_marks = $json['Response']['data']['inventory']['currencies'][1]['value'];
        }

        $data->grimoire = $json['Response']['data']['grimoireScore'];

        $charactersCount = count($data->characters);

        // characters
        $chars = [];
        for ($i = 0; $i <= 3; $i++)
        {
            if (isset($json['Response']['data']['characters'][$i]))
            {
                $chars[$i] = $this->updateOrAddCharacter($url, $json['Response']['data']['characters'][$i]);
                $pair = "character_" . ($i + 1);
                $data->$pair = $json['Response']['data']['characters'][$i]['characterBase']['characterId'];
            }
        }

        if ($charactersCount > 3)
        {
            // we have too many characters due to deletions, delete any that don't have the ID anymore
            $characters = $data->characters;
            $allowed = $data->characterIds();

            foreach ($characters as $char)
            {
                if (! in_array($char->characterId, $allowed))
                {
                    $char->delete();
                }
            }
        }

        // check for inactivity
        $this->tabulateActivity($data, $chars);

        $data->save();

        return $account;
    }

    /**
     * @param /Onyx/Account $account
     * @return array|null
     */
    public function getBungieProfile($account)
    {
        $url = sprintf(Constants::$getBungieAccount, $account->destiny->membershipId, $account->accountType);

        $json = $this->getJson($url);

        return isset($json['Response']['bungieNetUser']) ? $json['Response']['bungieNetUser'] : null;
    }

    /**
     * @return bool
     * @throws Helpers\Network\BungieOfflineException
     */
    public function getXurData()
    {
        $key = 'xur';

        if (Cache::has($key))
        {
            return Cache::get($key);
        }
        else
        {
            $url = Constants::$xurData;
            $json = $this->getJson($url);

            if (! isset($json['Response']['data']))
            {
                return false; // no xur data
            }
            else
            {
                $translator = new Hashes();
                $translator->setUrl($url);

                $items = '<strong>Xur Items</strong><br/><br />';

                foreach($json['Response']['data']['saleItemCategories'] as $category)
                {
                    if ($category['categoryTitle'] == "Exotic Gear")
                    {
                        foreach($category['saleItems'] as $item)
                        {
                            if (isset($item['item']['stats']) && count($item['item']['stats']) > 0)
                            {
                                $items .= "<strong>" . $translator->map($item['item']['itemHash'], true) . '</strong>' .
                                    ' - <a href="' . $this->getItemUrl($item['item']['itemHash']) . '">url' . '</a><br />';

                                foreach ($item['item']['stats'] as $stat)
                                {
                                    if ($stat['value'] != 0)
                                    {
                                        $items .= '  -->' . $translator->map($stat['statHash'], true) . ": " . number_format($stat['value'])  . "<br />";
                                    }

                                }
                                $items .= '<br />';
                            }
                        }
                    }
                }

                Cache::put($key, $items, 120);
                return $items;
            }
        }
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    /**
     * @param $id
     * @return string
     */
    private function getItemURL($id)
    {
        return sprintf(Constants::$ggItem, $id);
    }

    private function updateGameForNewField($data, $game)
    {
        $game->version = config('app.version', 1);

        if (isset($data['Response']['data']['activityDetails']['mode']) &&
            Gametype::isPVP($data['Response']['data']['activityDetails']['mode']))
        {
            $pvp = PVP::where('instanceId', $game->instanceId)->first();

            $entries = $data['Response']['data']['entries'];

            // delete old game-players
            GamePlayer::where('game_id', $game->instanceId)->delete();

            foreach($entries as $entry)
            {
                $this->gamePlayerSetup($data, $entry, $game, $pvp, false);
            }
        }

        $game->save();
    }

    /**
     * @param string $url
     * @param array $data
     * @param string $type
     */
    private function createGame($url, $data, $type)
    {
        $entries = $data['Response']['data']['entries'];

        $game = new Game();
        $game->setTranslatorUrl($url);

        $game->instanceId = $data['Response']['data']['activityDetails']['instanceId'];
        $game->referenceId = $data['Response']['data']['activityDetails']['referenceId'];
        $game->version = config('app.version', 1);

        if (isset($data['Response']['data']['activityDetails']['mode']) &&
            Gametype::isPVP($data['Response']['data']['activityDetails']['mode']))
        {
            // delete old PVP-Games
            PVP::where('instanceId', $game->instanceId)->delete();

            // create new one
            $pvp = new PVP();
            $pvp->instanceId = $game->instanceId;
            $pvp->gametype = $data['Response']['data']['activityDetails']['mode'];

            foreach($data['Response']['data']['teams'] as $team)
            {
                if ($team['standing']['basic']['value'] == 0) // 0 = victory
                {
                    $pvp->winnerPts = $team['score']['basic']['value'];
                    $pvp->winnerId = $team['teamId'];
                }
                elseif ($team['standing']['basic']['value'] == 1) // 1 = defeat
                {
                    $pvp->loserPts = $team['score']['basic']['value'];
                    $pvp->loserId = $team['teamId'];
                }
                else
                {
                    Log::warning('Unknown Team');
                }
            }

            $pvp->save();
        }

        // delete old game-players
        GamePlayer::where('game_id', $game->instanceId)->delete();

        $game->type = $type;
        $game->occurredAt = $data['Response']['data']['period'];

        $time = [];
        foreach($entries as $entry)
        {
            $time[] = $this->gamePlayerSetup($data, $entry, $game, isset($pvp) ? $pvp : null);
        }

        // get highest $duration (MODE)
        if (is_array($time))
        {
            $time = array_filter($time);
            $game->timeTookInSeconds = Text::array_mode($time);
        }
        $game->save();
    }

    /**
     * @param $data
     * @param $entry
     * @param $game
     * @param $pvp
     * @param bool $regular
     * @return null
     */
    private function gamePlayerSetup($data, $entry, &$game, $pvp, $regular = true)
    {
        $player = new GamePlayer();
        $player->game_id = $game->instanceId;
        $player->membershipId = $entry['player']['destinyUserInfo']['membershipId'];

        $guardian = $entry['player']['destinyUserInfo']['displayName'];
        $type = $entry['player']['destinyUserInfo']['membershipType'];

        // check if we have player
        if (($account = $this->checkCacheForGamertag($guardian, $type)) == false && $regular)
        {
            $account = Bus::dispatch(new UpdateGamertag($guardian, $type));
            return $this->gamePlayerSetup($data, $entry, $game, $pvp, $regular);
        }
        $player->account_id = $account->id;

        $player->characterId = $entry['characterId'];
        $player->level = $entry['player']['characterLevel'];
        $player->class = $entry['player']['characterClass'];
        $player->emblem = $entry['player']['destinyUserInfo']['iconPath'];

        $player->assists = $entry['values']['assists']['basic']['value'];
        $player->deaths = $entry['values']['deaths']['basic']['value'];
        $player->kills = $entry['values']['kills']['basic']['value'];
        $player->completed = boolval($entry['values']['completed']['basic']['value']);

        // PVP games don't seem to have secondsPlayed or averageLifespan
        if (isset($entry['values']['secondsPlayed']['basic']['value']))
        {
            $player->secondsPlayed = $entry['values']['secondsPlayed']['basic']['value'];
        }

        if (isset($entry['extended']['values']['averageLifespan']['basic']['value']))
        {
            $player->averageLifespan = $entry['extended']['values']['averageLifespan']['basic']['value'];
        }

        if (isset($entry['values']['score']['basic']['value']))
        {
            $player->score = $entry['values']['score']['basic']['value'];
        }

        if (isset($entry['values']['standing']['basic']['values']))
        {
            $player->standing = $entry['values']['standing']['basic']['value'];
        }

        // Check for team or rumble
        if (isset($entry['values']['team']['basic']['value']))
        {
            $player->team = $entry['values']['team']['basic']['value'];
        }

        // Check for revives given/received
        if (isset($entry['extended']['values']['resurrectionsPerformed']['basic']['value']))
        {
            $player->revives_given = $entry['extended']['values']['resurrectionsPerformed']['basic']['value'];
        }

        if (isset($entry['extended']['values']['resurrectionsReceived']['basic']['value']))
        {
            $player->revives_taken = $entry['extended']['values']['resurrectionsReceived']['basic']['value'];
        }

        if (isset($entry['extended']['values']['medalsActivityCompleteVictoryMercy']['basic']['value']))
        {
            $game->mercy = true;
            $game->save();
        }

        // Don't save if 0/0
        if ($player->score == 0 && $player->deaths == 0 && $player->kills == 0)
        {
            return;
        }
        else
        {
            $player->save();

            $duration = $entry['values']['activityDurationSeconds']['basic']['value'];

            if (isset($data['Response']['data']['activityDetails']['mode']) &&
                Gametype::isPVP($data['Response']['data']['activityDetails']['mode']))
            {
                // We need to figure out which "team" is PandaLove via checking the players
                if ($player->account->isPandaLove())
                {
                    if ($entry['standing'] == 0) // Victory
                    {
                        $pvp->pandaId = $pvp->winnerId;
                    }
                    else
                    {
                        $pvp->pandaId = $pvp->loserId;
                    }

                    $pvp->save();
                }
            }
        }

        return isset($duration) ? $duration : null;
    }

    /**
     * @param string $url
     * @param array $data
     * @return bool
     */
    private function updateOrAddCharacter($url, $data)
    {
        $activity = false;
        $charBase = $data['characterBase'];

        $character = Character::where('characterId', $charBase['characterId'])->first();

        if ( ! $character instanceof Character)
        {
            $character = new Character();
            $character->membershipId = $charBase['membershipId'];
            $character->characterId = $charBase['characterId'];
        }
        else
        {
            $activity = $this->checkForActivity($character, $charBase['minutesPlayedTotal']);
        }

        $character->setTranslatorUrl($url);

        $character->realLevel = $charBase['powerLevel']; // deprecated as of TTK
        $character->next_level_exp = $data['levelProgression']['nextLevelAt'];
        $character->progress_exp = $data['levelProgression']['progressToNextLevel'];

        $character->last_played = new Carbon($charBase['dateLastPlayed']);
        $character->minutes_played = $charBase['minutesPlayedTotal'];
        $character->minutes_played_last_session = $charBase['minutesPlayedThisSession'];
        $character->level = $data['characterLevel'];
        $character->race = $charBase['raceHash'];
        $character->gender = $charBase['genderHash'];
        $character->class = $charBase['classHash'];
        $character->defense = $charBase['stats']['STAT_DEFENSE']['value'];
        $character->intellect = $charBase['stats']['STAT_INTELLECT']['value'];
        $character->discipline = $charBase['stats']['STAT_DISCIPLINE']['value'];
        $character->strength = $charBase['stats']['STAT_STRENGTH']['value'];

        if (isset($charBase['stats']['STAT_LIGHT']))
        {
            $character->light = $charBase['stats']['STAT_LIGHT']['value'];

            // apply highest light they've earned on this char.
            if (isset($character->highest_light))
            {
                $character->highest_light = max($character->light, $character->highest_light);
            }
            else
            {
                $character->highest_light = $character->light;
            }
        }
        else
        {
            // under lvl20, no LIGHT
            $character->light = 0;
        }

        $character->subclass = $charBase['peerView']['equipment'][0]['itemHash'];
        $character->helmet = $charBase['peerView']['equipment'][1]['itemHash'];
        $character->arms = $charBase['peerView']['equipment'][2]['itemHash'];
        $character->chest = $charBase['peerView']['equipment'][3]['itemHash'];
        $character->boots = $charBase['peerView']['equipment'][4]['itemHash'];
        $character->class_item = $charBase['peerView']['equipment'][5]['itemHash'];

        $character->primary = $charBase['peerView']['equipment'][6]['itemHash'];
        $character->secondary = $charBase['peerView']['equipment'][7]['itemHash'];
        $character->heavy = $charBase['peerView']['equipment'][8]['itemHash'];
        $character->ship = $charBase['peerView']['equipment'][9]['itemHash'];

        $this->setEquipmentField($character, $charBase, 'sparrow', 10);
        $this->setEquipmentField($character, $charBase, 'ghost', 11);
        $this->setEquipmentField($character, $charBase, 'background', 12);
        $this->setEquipmentField($character, $charBase, 'shader', 13);
        $this->setEquipmentField($character, $charBase, 'emote', 14);
        $this->setEquipmentField($character, $charBase, 'horn', 15);
        $this->setEquipmentField($character, $charBase, 'artifact', 16);

        $character->emblem = $data['emblemHash'];
        $character->save();
        return $activity;
    }

    /**
     * @param $character
     * @param $data
     * @param $name
     * @param $id
     */
    private function setEquipmentField(&$character, &$data, $name, $id)
    {
        if (isset($data['peerView']['equipment'][$id]['itemHash']))
        {
            $character->{$name} = $data['peerView']['equipment'][$id]['itemHash'];
        }
    }

    /**
     * @param $gamertag
     * @param $console
     * @return \Onyx\Account|void
     */
    private function checkCacheForGamertag($gamertag, $console = Console::Xbox)
    {
        $account = Account::where('seo', Text::seoGamertag($gamertag))
            ->where('accountType', $console)
            ->first();

        if ($account instanceof Account)
        {
            return $account;
        }

        return false;
    }

    /**
     * @param $console
     * @param $gamertag
     * @return bool|Account
     */
    private function checkCacheForGamertagByConsole($console, $gamertag)
    {
        /** @var Account $account */
        $account = Account::with('destiny')
            ->where('seo', Text::seoGamertag($gamertag))
            ->where('accountType', $console)
            ->first();

        if ($account instanceof Account && $account->destiny !== null)
        {
            return $account;
        }

        return false;
    }

    /**
     * @param \Onyx\Destiny\Objects\Data $destiny
     * @param array $chars
     */
    private function tabulateActivity($destiny, $chars)
    {
        $reset = false;

        foreach($chars as $key => $value)
        {
            if ($value)
            {
                $reset = true;
            }
        }

        if ($reset)
        {
            $destiny->inactiveCounter = 0;
            $destiny->save();
        }
        else
        {
            $destiny->inactiveCounter++;
            $destiny->save();
        }
    }

    /**
     * @param \Onyx\Destiny\Objects\Character $char
     * @return bool
     */
    private function checkForActivity($char, $new_minutes)
    {
        $minutes_old = $char->getMinutesPlayedRaw();

        if ($minutes_old != $new_minutes)
        {
            return true;
        }

        return false;
    }
}

class PlayerNotFoundException extends \Exception {};
class GameNotFoundException extends \Exception {};