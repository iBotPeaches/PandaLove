<?php

namespace Onyx\Overwatch\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Onyx\Account;

/**
 * Class Stats.
 *
 * @property int $id
 * @property int $account_id
 * @property int $season
 * @property float $healing_done_avg
 * @property float $deaths_avg
 * @property float $damage_done_avg
 * @property float $final_blows_avg
 * @property float $eliminations_avg
 * @property float $time_spent_on_fire_avg
 * @property float $solo_kills_avg
 * @property float $melee_final_blows_avg
 * @property float $objective_kills_avg
 * @property float $objective_time_avg
 * @property int $ties
 * @property int $losses
 * @property int $wins
 * @property int $level
 * @property float $win_rate
 * @property int $prestige
 * @property int $games
 * @property int $comprank
 * @property int $max_comprank
 * @property string $tier
 * @property string $avatar
 * @property string $rank_image
 * @property int $damage_done_most_in_game
 * @property int $objective_kills
 * @property float $time_spent_on_fire
 * @property int $eliminations_most_in_game
 * @property int $medals_bronze
 * @property int $games_won
 * @property int $teleporter_pad_destroyed_most_in_game
 * @property int $final_blows
 * @property int $deaths
 * @property float $time_spent_on_fire_most_in_game
 * @property int $games_lost
 * @property int $offensive_assists_most_in_game
 * @property int $turrets_destroyed
 * @property float $defensive_assists_most_in_game
 * @property int $turrets_destroyed_most_in_game
 * @property int $teleporter_pad_destroyed
 * @property int $healing_done
 * @property int $medals_silver
 * @property int $multikills
 * @property int $multikill
 * @property int $medals_gold
 * @property int $eliminations
 * @property int $multikill_best
 * @property int $cards
 * @property int $objective_kills_most_in_game
 * @property int $offensive_assists
 * @property int $games_played
 * @property int $environmental_kills
 * @property int $environmental_kills_most_in_game
 * @property float $kpd
 * @property int $environmental_deaths
 * @property int $kill_streak_best
 * @property int $healing_done_most_in_game
 * @property int $solo_kills
 * @property int $final_blows_most_in_game
 * @property int $solo_kills_most_in_game
 * @property int $time_played
 * @property int $melee_final_blow_in_game
 * @property int $damage_done
 * @property int $objective_time
 * @property int $medals
 * @property int $games_tied
 * @property int $melee_final_blows
 * @property int $defensive_assists
 * @property int $shots_fired
 * @property int $ultimates_earned
 * @property float $time_holding_ultimate
 * @property int $damage_blocked
 * @property int $ultimates_used
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $inactive_counter
 * @property Account $account
 * @property Collection $characters
 */
class Stats extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'overwatch_stats';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    public $appends = ['totalLevel'];

    public static function boot()
    {
        parent::boot();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setComprankAttribute($value)
    {
        if ($this->max_comprank < $value) {
            $this->attributes['max_comprank'] = $value;
        }

        $this->attributes['comprank'] = $value;
    }

    public function getTierAttribute($value)
    {
        return ucfirst($value);
    }

    public function getTotalLevelAttribute($value)
    {
        return $this->totalLevel();
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }

    public function characters()
    {
        return $this->hasMany('Onyx\Overwatch\Objects\Character', 'account_id', 'id')->orderBy('playtime', 'DESC');
    }

    public function mainCharacter()
    {
        return $this->characters->sortByDesc('playtime')->first();
    }

    public function randomCharacter() : Character
    {
        return $this->characters->filter(function (Character $character) {
            return $character->playtime >= .10;
        })->random(1);
    }

    public function specificCharacter(string $char)
    {
        return $this->characters->first(function (int $key, Character $character) use ($char) {
            return $character->getOriginal('character') == $char;
        });
    }

    public function getLastUpdatedRelative()
    {
        $date = new Carbon($this->updated_at);

        return $date->diffForHumans();
    }

    public function totalLevel()
    {
        return (100 * $this->prestige) + $this->level;
    }

    public function highestPlaytime()
    {
        return $this->mainCharacter()->playtime;
    }

    public function winRate()
    {
        return round($this->win_rate, 2);
    }

    public function winRateColor()
    {
        $winRate = $this->winRate();

        switch (true) {
            case $winRate >= 65:
                return 'green';

            case $winRate >= 35 && $winRate < 65:
                return 'yellow';

            case $winRate < 35:
                return 'red';
        }
    }
}
