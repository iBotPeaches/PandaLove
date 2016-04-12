<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class Match
 * @package Onyx\Halo5\Objects
 * @property Uuid $uuid
 * @property string $map_variant
 * @property string $game_variant
 * @property string $playlist_id
 * @property string $map_id
 * @property string $gamebase_id
 * @property string $season_id
 * @property boolean $isTeamGame
 *
 * @property Map $map
 * @property Gametype $gametype
 * @property Season $season
 * @property Playlist $playlist
 * @property MatchTeam[] $teams
 * @property MatchEvent[] $events
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
    protected $guarded = ['uuid'];

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * Disable timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public $incrementing = false;

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

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function isArena()
    {
        if (count($this->teams) <= 2)
        {
            if (strpos($this->playlist->name, 'social') !== false)
            {
                return in_array('Arena', $this->gametype->game_modes);
            }
        }

        return false;
    }

    public function playersOnTeam($key)
    {
        return $this->players->where('team_id', $key);
    }
    
    public function events()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchEvent', 'game_id', 'uuid')->orderBy('seconds_since_start');
    }

    public function players()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchPlayer', 'game_id', 'uuid');
    }

    public function teams()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchTeam', 'game_id', 'uuid')->orderBy('rank');
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
