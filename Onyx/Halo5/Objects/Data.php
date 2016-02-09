<?php namespace Onyx\Halo5\Objects;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Halo5\Helpers\Date\DateIntervalFractions;

/**
 * @package \Onyx\Halo5\Objects
 * @property int $id
 * @property int $account_id
 * @property int $totalKills
 * @property int $totalSpartanKills
 * @property int $totalHeadshots
 * @property int $totalDeaths
 * @property int $totalAssists
 * @property int $totalGames
 * @property int $totalGamesWon
 * @property int $totalGamesLost
 * @property int $totalGamesTied
 * @property int $totalTimePlayed
 * @property int $spartanRank
 * @property int $Xp
 * @property array $medals
 * @property null $emblem
 * @property null $spartan
 * @property int $highest_CsrTier
 * @property int $highest_CsrDesignationId
 * @property int $highest_Csr
 * @property int $highest_percentNext
 * @property int $highest_rank
 * @property string $highest_CsrPlaylistId
 * @property string $highest_CsrSeasonId
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $seasonId
 * @property int $inactiveCounter
 */
class Data extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_data';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Disable timestamps
     *
     * @var bool
     */
    public $timestamps = true;

    public static function boot()
    {
        parent::boot();

        Data::deleting(function($h5)
        {
            $h5->playlists->delete();
        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setMedalsAttribute($value)
    {
        if (is_array($value))
        {
            $insert = [];

            foreach($value as $medal)
            {
                $insert[$medal['MedalId']] = $medal['Count'];
            }
            $this->attributes['medals'] = json_encode($insert);
        }
    }

    public function setTotalTimePlayedAttribute($value)
    {
        $this->attributes['totalTimePlayed'] = DateHelper::returnSeconds($value);
    }

    public function getMedalsAttribute($value)
    {
        return json_decode($value, true);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }

    /**
     * @return Collection|\Onyx\Halo5\Objects\PlaylistData
     */
    public function playlists()
    {
        return $this->hasMany('Onyx\Halo5\Objects\PlaylistData', 'account_id', 'account_id')
            ->orderBy('highest_CsrDesignationId', 'DESC')
            ->orderBy('highest_Csr', 'DESC')
            ->orderBy('highest_CsrTier', 'DESC')
            ->orderBy('measurementMatchesLeft', 'ASC');
    }

    public function warzone()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Warzone', 'account_id', 'account_id');
    }

    public function season()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Season', 'contentId', 'seasonId');
    }

    public function record_playlist()
    {
        // setup fake PlaylistData with the elements of the best playlist
        // This is bad because we can't leverage pre-loading of data (eager load)
        // so we have n+1 queries here.
        // @todo store highest CSR in a different table to allow eager loading

        $record = new PlaylistData();
        $record->fill($this->highest_playlist());

        if ($record->stock instanceof Playlist)
        {
            return $record;
        }

        return null;
    }

    public function highest_playlist()
    {
        return array(
            'highest_CsrTier'           => $this->highest_CsrTier,
            'highest_CsrDesignationId'  => $this->highest_CsrDesignationId,
            'highest_Csr'               => $this->highest_Csr,
            'highest_percentNext'       => $this->highest_percentNext,
            'highest_rank'              => $this->highest_rank,
            'highest_CsrPlaylistId'     => $this->highest_CsrPlaylistId,
            'playlistId'                => $this->highest_CsrPlaylistId
        );
    }

    public function getSpartan()
    {
        return asset('uploads/h5/' . $this->account->seo . '/spartan.png');
    }

    public function getEmblem()
    {
        return asset('uploads/h5/' . $this->account->seo . '/emblem.png');
    }

    public function getLastUpdatedRelative()
    {
        $date = new Carbon($this->updated_at);

        return $date->diffForHumans();
    }

    public function kd($formatted = true)
    {
        if ($formatted)
            return number_format($this->_raw_kd(), 2);
        else
            return $this->_raw_kd();
    }

    public function kad()
    {
        if ($this->totalDeaths == 0)
        {
            return ($this->totalKills + $this->totalAssists);
        }

        return number_format(($this->totalKills + $this->totalAssists) / $this->totalDeaths, 2);
    }

    public function winRate()
    {
        return round(($this->totalGamesWon / $this->totalGames) * 100);
    }

    public function winRateColor()
    {
        $rate = $this->winRate();

        switch (true)
        {
            case $rate > 80:
                return 'green';

            case $rate <= 80 && $rate > 60:
                return 'yellow';

            case $rate <= 60 && $rate > 40:
                return 'orange';

            default:
                return 'red';
        }
    }

    private function _raw_kd()
    {
        if ($this->totalDeaths == 0)
        {
            return $this->totalKills;
        }

        return $this->totalKills / $this->totalDeaths;
    }

}
