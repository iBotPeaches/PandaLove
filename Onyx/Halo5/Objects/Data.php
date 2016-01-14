<?php namespace Onyx\Halo5\Objects;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Halo5\Helpers\Date\DateIntervalFractions;

/**
 * Class Data
 * @package \Onyx\Halo5\Objects
 * @property int $highest_CsrTier
 * @property int $totalGames
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
            ->orderBy('measurementMatchesLeft', 'ASC');
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

    public function kd()
    {
        if ($this->totalDeaths == 0)
        {
            return $this->totalKills;
        }

        return number_format($this->totalKills / $this->totalDeaths, 2);
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

}
