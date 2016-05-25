<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Enums\EventName;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Laravel\Helpers\Text;
use Ramsey\Uuid\Uuid;

/**
 * Class Match
 * @package Onyx\Halo5\Objects
 * @property Uuid $uuid
 * @property integer $id
 * @property string $map_variant
 * @property string $game_variant
 * @property string $playlist_id
 * @property string $map_id
 * @property string $gamebase_id
 * @property string $season_id
 * @property boolean $isTeamGame
 * @property integer $duration
 *
 * @property Map $map
 * @property Gametype $gametype
 * @property Season $season
 * @property Playlist $playlist
 * @property MatchTeam[] $teams
 * @property MatchEvent[] $events
 * @property MatchEvent[] $kill_events
 * @property MatchPlayer[] $players
 */
class Match extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_matches';

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

    public function setSeasonIdAttribute($value)
    {
        if (strlen($value) > 1)
        {
            $this->attributes['season_id'] = $value;
        }
        else
        {
            $this->attributes['season_id'] = null;
        }
    }
    
    public function setDurationAttribute($value)
    {
        $this->attributes['duration'] = DateHelper::returnSeconds($value);
    }

    public function getDurationAttribute($value)
    {
        return Text::timeDuration($value);
    }

    public function getIsTeamGameAttribute($value)
    {
        return boolval($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function isArena()
    {
        return $this->playlist->isRanked;
    }

    /**
     * @return bool|int|mixed
     */
    public function hasRounds()
    {
        if (isset($this->rounds))
        {
            return $this->rounds;
        }
        
        $max = 0;
        foreach ($this->teams as $team)
        {
            if (count($team->round_stats) > 1)
            {
                $max = max($max, count($team->round_stats));
            }
        }

        $this->rounds = $max;
        return ($max == 0) ? false : $max;
    }

    /**
     * @return null|MatchTeam
     */
    public function winner()
    {
        foreach ($this->teams as $team)
        {
            if ($team->isWinner())
            {
                return $team;
            }
        }
        
        return null;
    }

    public function playersOnTeam($key)
    {
        return $this->players->where('team_id', $key);
    }
    
    public function events()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchEvent', 'game_id', 'id')->orderBy('seconds_since_start');
    }

    public function kill_events()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchEvent', 'game_id', 'id')
            ->where('event_name', EventName::Death)
            ->orderBy('seconds_since_start');
    }

    /**
     * @return MatchPlayer[]
     */
    public function players()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchPlayer', 'game_id', 'id');
    }

    /**
     * @return MatchTeam[]
     */
    public function teams()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchTeam', 'game_id', 'id')->orderBy('rank');
    }

    public function map()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Map', 'uuid', 'map_id');
    }

    public function gametype()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Gametype', 'uuid', 'gamebase_id');
    }

    public function playlist()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Playlist', 'contentId', 'playlist_id');
    }

    public function season()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Season', 'contentId', 'season_id');
    }
}
