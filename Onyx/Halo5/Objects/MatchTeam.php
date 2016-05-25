<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class MatchTeam
 * @package Onyx\Halo5\Objects
 * @property integer $id
 * @property string $game_id
 * @property integer $team_id
 * @property integer $score
 * @property integer $rank
 * @property array $round_stats
 * @property string $key
 *
 * @property Team $team
 */
class MatchTeam extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_matches_teams';

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

        static::creating(function ($team)
        {
            if ($team->team_id === null)
            {
                $team->key = ($team->game_id . "_" . $team->player_id);
                unset($team->player_id);
            }
        });

        static::created(function ($team)
        {
            /* @var $team \Onyx\Halo5\Objects\MatchTeam */
            if ($team->team_id !== null)
            {
                $team->key = ($team->game_id . "_" . $team->team_id);
                $team->save();
            }
        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setRoundStatsAttribute($value)
    {
        if (is_array($value))
        {
            $this->attributes['round_stats'] = json_encode($value);
        }
        else
        {
            $this->attributes['round_stats'] = null;
        }
    }

    public function setUuidAttribute($value)
    {
        if ($value instanceof Uuid)
        {
            $this->attributes['uuid'] = $value->toString();
        }
    }

    public function getRoundStatsAttribute($value)
    {
        return json_decode($value, true);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function getRoundStats($roundId)
    {
        foreach ($this->round_stats as $round)
        {
            if ($round['RoundNumber'] == ($roundId + 1))
            {
                return $round;
            }
        }

        return false;
    }

    public function getSemanticColor()
    {
        return $this->team->getSemanticColor();
    }

    public function label()
    {
        if ($this->isWinner())
        {
            return '<span class="ui green label">Win</span>';
        }
        return '<span class="ui label">Lose</span>';
    }

    public function isWinner()
    {
        return $this->rank == 1;
    }

    public function team()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Team', 'id', 'team_id');
    }
}
