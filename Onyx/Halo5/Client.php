<?php namespace Onyx\Halo5;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Halo5\Helpers\Network\Http;
use Onyx\Halo5\Objects\Data;
use Onyx\Halo5\Objects\PlaylistData;

class Client extends Http {

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
        $url = sprintf(Constants::$servicerecord_arena, $gamertag);

        $account = $this->checkCacheForGamertag($gamertag);

        if ($account instanceof Account)
        {
            // lets check if they have H5 data
            if (! $account->h5 instanceof Data)
            {
                $h5_data = new Data();
                $h5_data->account_id = $account->id;
                $h5_data->save();
            }

            return $account;
        }

        $json = $this->getJson($url);

        if (isset($json['Response'][0]['membershipId'])) // @todo this check is wrong.
        {
            try
            {
                return Account::firstOrCreate([
                    'gamertag' => $json['Response'][0]['displayName']
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

    public function updateH5Account($account)
    {
        // 1) Grab new Service Record
        $this->updateArenaServiceRecord($account);

        // 2) Update Spartan Image
        $this->updateSpartan($account);

        // 3) Update Emblem Image
        $this->updateEmblem($account);
    }

    public function updateEmblem($account, $size = 256)
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

    public function updateSpartan($account, $size = 512)
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

    public function updateArenaServiceRecord($account)
    {
        $record = $this->_getArenaServiceRecord($account);
        $h5_data = $account->h5;

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

        if ($record['ArenaStats']['HighestCsrAttained'] != null)
        {
            $h5_data->highest_CsrTier = $record['ArenaStats']['HighestCsrAttained']['Tier'];
            $h5_data->highest_CsrDesignationId = $record['ArenaStats']['HighestCsrAttained']['DesignationId'];
            $h5_data->highest_Csr = $record['ArenaStats']['HighestCsrAttained']['Csr'];
            $h5_data->highest_percentNext = $record['ArenaStats']['HighestCsrAttained']['PercentToNextTier'];
            $h5_data->highest_rank = $record['ArenaStats']['HighestCsrAttained']['Rank'];
            $h5_data->highest_CsrPlaylistId = $record['ArenaStats']['HighestCsrPlaylistId'];
        }

        // clear out old playlist history, dump new playlists
        PlaylistData::where('account_id', $account->id)->delete();
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
            $p->save();
        }

        $h5_data->save();
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

    public function getCsrs()
    {
        $url = Constants::$metadata_csr;

        return $this->getJson($url);
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    private function _getEmblemImage($account, $size = 256)
    {
        $url = sprintf(Constants::$emblem_image, $account->seo, $size);
        return $this->getAsset($url);
    }

    private function _getSpartanImage($account, $size = 512)
    {
        $url = sprintf(Constants::$spartan_image, $account->seo, $size);
        return $this->getAsset($url);
    }

    private function _getArenaServiceRecord($account)
    {
        $url = sprintf(Constants::$servicerecord_arena, $account->gamertag);
        $json = $this->getJson($url);

        if (isset($json['Results'][0]['ResultCode']) && $json['Results'][0]['ResultCode'] == 0)
        {
            return $json['Results'][0]['Result'];
        }
    }

    /**
     * @param $gamertag
     * @return \Onyx\Account|void
     */
    private function checkCacheForGamertag($gamertag)
    {
        $account = Account::where('seo', Text::seoGamertag($gamertag))->first();

        if ($account instanceof Account)
        {
            return $account;
        }

        return false;
    }
}

class H5PlayerNotFoundException extends \Exception {};
