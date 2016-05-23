<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\CustomTraits\Stats;
use Onyx\Halo5\Helpers\Date\DateHelper;

/**
 * Class PlaylistData
 * @package Onyx\Halo5\Objects
 * @property int $id
 * @property int $account_id
 * @property string $playlistId
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
 * @property int $highest_CsrTier
 * @property int $highest_CsrDesignationId
 * @property int $highest_Csr
 * @property int $highest_percentNext
 * @property int $highest_rank
 * @property int $current_CsrTier
 * @property int $current_CsrDesignationId
 * @property int $current_Csr
 * @property int $current_percentNext
 * @property int $current_rank
 * @property int $csrPercentile
 * @property int measurementMatchesLeft
 * @property string $seasonId
 */
class PlaylistData extends Model {

    use Stats;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_playlists_data';

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

    public function setHighestRankAttribute($value)
    {
        $this->attributes['highest_rank'] = (is_null($value) ? 0 : $value);
    }

    public function setCurrentRankAttribute($value)
    {
        $this->attributes['current_rank'] = (is_null($value) ? 0 : $value);
    }

    public function setTotalTimePlayedAttribute($value)
    {
        $this->attributes['totalTimePlayed'] = DateHelper::returnSeconds($value);
    }

    public function setCsrPercentileAttribute($value)
    {
        $this->attributes['csrPercentile'] = ($value != null) ? ($value) : null;
    }

    public function getDesignationIdAttribute($value)
    {
        return intval($value);
    }

    public function getCsrPercentileAttribute($value)
    {
        return ($value != null) ? $value . "%" : '?';
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function account()
    {
        return $this->belongsTo('Onyx\Account', 'id', 'account_id');
    }

    public function h5()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Data', 'account_id', 'account_id');
    }

    public function stock()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Playlist', 'playlistId', 'contentId');
    }

    public function high_csr()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\CSR', 'highest_CsrDesignationId', 'designationId');
    }

    public function current_csr()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\CSR', 'current_CsrDesignationId', 'designationId');
    }

    public function season()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Season', 'contentId', 'seasonId');
    }

    public function getGamesDone()
    {
        return 10 - $this->measurementMatchesLeft;
    }

    public function tier($type = 'highest')
    {
        $action = $type == 'highest' ? 'highest_CsrTier' : 'current_CsrTier';

        if ($this->measurementMatchesLeft != 0)
        {
            return $this->getGamesDone();
        }

        return $this->$action;
    }

    public function title($type = 'highest')
    {
        $action = $type == 'highest' ? 'high_csr' : 'current_csr';
        $tier = $type == 'highest' ? 'highest_CsrTier' : 'current_CsrTier';
        $designationId = $type == 'highest' ? 'highest_CsrDesignationId' : 'current_CsrDesignationId';

        switch ($this->$designationId)
        {
            case 0: // Unranked
            case 6: // SemiPro
            case 7: // Pro
                return $this->$action->name;

            default:
                return $this->$action->name . " " . $this->$tier;
        }
    }

    public function rank($type = 'highest')
    {
        $action = $type == 'highest' ? 'highest_rank' : 'current_rank';

        switch ($this->$action)
        {
            case 1:
                return '1st';

            case 2:
                return '2nd';

            case 3:
                return '3rd';

            default:
                return $this->$action . 'th';
        }
    }

    /**
     * @return string
     */
    public function rosterTitle()
    {
        $title = $this->stock->name . " (" . $this->title() . ") ";

        if ($this->highest_Csr != 0 && $this->highest_rank == 0)
        {
            $title .= '' . number_format($this->highest_Csr) . ' CSR';
        }
        else if ($this->highest_Csr != 0 && $this->highest_rank != 0) {
            $title .= '' . $this->rank('highest') . ' place.';
        }

        return $title;
    }

    public function kd($formatted = true)
    {
        return self::stat_kd($this->totalSpartanKills, $this->totalDeaths, $formatted);
    }

    public function kad($formatted = true)
    {
        return self::stat_kad($this->totalSpartanKills, $this->totalDeaths, $this->totalAssists, $formatted);
    }

    public function winRate()
    {
        return $this->stat_winRate($this->totalGamesWon, $this->totalGames);
    }

    public function winRateColor()
    {
        return $this->stat_winRateColor($this->totalGamesWon, $this->totalGames);
    }
    
    public function percentileColor()
    {
        return $this->stat_percentileColor($this->getOriginal('csrPercentile'));
    }
}
