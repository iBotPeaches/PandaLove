<?php

namespace Onyx\Fortnite\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Account;
use Onyx\User;

/**
 * Class Stats.
 *
 * @property int $id
 * @property string $epic_id
 * @property int $account_id
 * @property int $user_id
 * @property int $solo_kills
 * @property int $solo_matchesplayed
 * @property int $solo_score
 * @property int $solo_minutesplayed
 * @property Carbon $solo_lastmodified
 * @property int $solo_top1
 * @property int $solo_top3
 * @property int $solo_top5
 * @property int $solo_top6
 * @property int $solo_top10
 * @property int $solo_top12
 * @property int $solo_top25
 * @property int $duo_kills
 * @property int $duo_matchesplayed
 * @property int $duo_score
 * @property int $duo_minutesplayed
 * @property Carbon $duo_lastmodified
 * @property int $duo_top1
 * @property int $duo_top3
 * @property int $duo_top5
 * @property int $duo_top6
 * @property int $duo_top10
 * @property int $duo_top12
 * @property int $duo_top25
 * @property int $squad_kills
 * @property int $squad_matchesplayed
 * @property int $squad_score
 * @property int $squad_minutesplayed
 * @property Carbon $squad_lastmodified
 * @property int $squad_top1
 * @property int $squad_top3
 * @property int $squad_top5
 * @property int $squad_top6
 * @property int $squad_top10
 * @property int $squad_top12
 * @property int $squad_top25
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $inactiveCounter
 * @property Account $account
 * @property User $user
 */
class Stats extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fortnite_stats';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public $dates = [
        'solo_lastmodified',
        'duo_lastmodified',
        'squad_lastmodified',
        'updated_at',
        'created_at',
    ];

    public static function boot()
    {
        parent::boot();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setSquadLastmodifiedAttribute($value)
    {
        $this->attributes['squad_lastmodified'] = Carbon::createFromTimestampUTC($value);
    }

    public function setDuoLastmodifiedAttribute($value)
    {
        $this->attributes['duo_lastmodified'] = Carbon::createFromTimestampUTC($value);
    }

    public function setSoloLastmodifiedAttribute($value)
    {
        $this->attributes['solo_lastmodified'] = Carbon::createFromTimestampUTC($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function getLastUpdatedRelative(): string
    {
        $date = new Carbon($this->updated_at);

        return $date->diffForHumans();
    }

    public function getMatchesSum(): int
    {
        return $this->squad_matchesplayed + $this->duo_matchesplayed + $this->solo_matchesplayed;
    }

    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
