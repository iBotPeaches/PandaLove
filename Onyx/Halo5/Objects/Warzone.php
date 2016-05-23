<?php namespace Onyx\Halo5\Objects;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\CustomTraits\Stats;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Halo5\Helpers\Date\DateIntervalFractions;

/**
 * @package \Onyx\Halo5\Objects
 * @property int $id
 * @property int $account_id
 * @property int $totalKills
 * @property int $totalHeadshots
 * @property int $totalDeaths
 * @property int $totalAssists
 * @property int $totalGames
 * @property int $totalGamesWon
 * @property int $totalGamesLost
 * @property int $totalGamesTied
 * @property int $totalTimePlayed
 * @property int $totalPiesEarned
 * @property array $medals
 * @property array $weapons
 */
class Warzone extends Model {

    use Stats;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_warzone';

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
    public $timestamps = false;

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

    public function setWeaponsAttribute($value)
    {
        if (is_array($value))
        {
            $insert = [];

            foreach($value as $weapon)
            {
                $insert[$weapon['WeaponId']['StockId']] = $weapon['TotalKills'];
            }

            arsort($insert);
            $this->attributes['weapons'] = json_encode($insert);
        }
    }

    public function setTotalTimePlayedAttribute($value)
    {
        if (strlen($value) > 1)
        {
            $this->attributes['totalTimePlayed'] = DateHelper::returnSeconds($value);
        }
        else
        {
            $this->attributes['totalTimePlayed'] = 0;
        }
    }

    public function getMedalsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getWeaponsAttribute($value)
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

    public function getLastUpdatedRelative()
    {
        $date = new Carbon($this->updated_at);

        return $date->diffForHumans();
    }

    public function kd($formatted = true)
    {
        return self::stat_kd($this->totalKills, $this->totalDeaths, $formatted);
    }

    public function kad($formatted = true)
    {
        return self::stat_kad($this->totalKills, $this->totalDeaths, $this->totalAssists, $formatted);
    }

    public function winRate()
    {
        return $this->stat_winRate($this->totalGamesWon, $this->totalGames);
    }

    public function winRateColor()
    {
        return $this->stat_winRateColor($this->totalGamesWon, $this->totalGames);
    }
}
