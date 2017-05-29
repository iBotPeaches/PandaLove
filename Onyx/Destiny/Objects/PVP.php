<?php

namespace Onyx\Destiny\Objects;

use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Helpers\Utils\Gametype;
use Onyx\Destiny\Helpers\Utils\Team;

/**
 * Class PVP.
 *
 * @property int $id
 * @property int $instanceId
 * @property string $gametype
 * @property int $winnerPts
 * @property int $loserPts
 * @property int $winnerId
 * @property int $loserId
 * @property int $pandaIa
 */
class PVP extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'destiny_pvp_games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();
    }

    public static function boot()
    {
        parent::boot();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function getGametypeAttribute($value)
    {
        return Gametype::getGametype($value);
    }

    public function getWinnerPtsAttribute($value)
    {
        return number_format($value);
    }

    public function getLoserPtsAttribute($value)
    {
        return number_format($value);
    }

    public function getPandaIdAttribute($value)
    {
        return intval($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function game()
    {
        return $this->belongsTo('Onyx\Destiny\Objects\Game');
    }

    public function getTeamsInOrder()
    {
        return [$this->attributes['winnerId'], $this->attributes['loserId']];
    }

    public function color($team_id)
    {
        return Team::teamIdToColor($team_id);
    }

    public function pts($team_id)
    {
        if ($this->attributes['winnerId'] == $team_id) {
            return $this->winnerPts;
        } elseif ($this->attributes['loserId'] == $team_id) {
            return $this->loserPts;
        } else {
            return 0;
        }
    }

    public function opposite($team_id)
    {
        if ($team_id == 16) {
            return 17;
        } elseif ($team_id == 17) {
            return 16;
        } else {
            return 0;
        }
    }
}
