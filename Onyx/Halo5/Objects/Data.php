<?php namespace Onyx\Halo5\Objects;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Halo5\Helpers\Date\DateIntervalFractions;

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

    public function playlists()
    {
        return $this->hasMany('Onyx\Halo5\Objects\PlaylistData', 'account_id', 'account_id')
            ->orderBy('highest_CsrDesignationId', 'DESC')
            ->orderBy('highest_Csr', 'DESC')
            ->orderBy('measurementMatchesLeft', 'ASC');
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
