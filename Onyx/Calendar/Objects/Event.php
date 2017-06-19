<?php

namespace Onyx\Calendar\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Enums\Types;

/**
 * Class Event.
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property Carbon $start
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $max_players
 * @property bool $alert_5
 * @property bool $alert_15
 * @property string $game destiny|h5
 * @property Attendee[] $attendees
 */
class Event extends Model
{
    protected $table = 'calendar_game_events';

    protected $fillable = ['title', 'type', 'start', 'max_players', 'game'];

    protected $dates = ['start', 'created_at', 'updated_at'];

    public $timestamps = true;

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setStartAttribute($value)
    {
        $this->attributes['start'] = new Carbon(str_replace('"', null, $value), 'America/Chicago');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = str_replace('"', null, $value);
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = Types::getProperFormat($value);
    }

    public function getGameAttribute($value)
    {
        return $value == '' ? 'destiny' : $value;
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function humanDate()
    {
        return $this->start->format('F j - g:ia');
    }

    public function botDate()
    {
        return $this->start->format('M j (D) - g:ia');
    }

    public function count()
    {
        return count($this->attendees);
    }

    public function attendees()
    {
        return $this->hasMany('Onyx\Calendar\Objects\Attendee', 'game_id', 'id');
    }

    public function spotsRemaining()
    {
        return $this->max_players - count($this->attendees);
    }

    public function isFull()
    {
        return $this->max_players == count($this->attendees);
    }

    public function getBackgroundColor()
    {
        switch ($this->game) {
            case 'destiny':
                return '#5BBD72';

            case 'h5':
                return '#D95C5C';
        }
    }

    public function game_name()
    {
        switch ($this->game) {
            case 'destiny':
                return 'Destiny';

            case 'h5':
                return 'Halo 5';
        }
    }

    public function getHumanType()
    {
        switch ($this->type) {
            case 'ToO':
                return 'Trials of Osiris';

            case 'Raid':
            case 'Flawless':
                return 'Raid';

            case 'PoE':
                return 'Prison Of Elders';

            case 'PVP':
                return 'PVP';
        }
    }

    public function getPlayerDefaultSize()
    {
        switch ($this->game) {
            case 'destiny':
                switch ($this->type) {
                    case 'ToO':
                    case 'PoE':
                        return 3;

                    case 'Raid':
                    case 'Flawless':
                    case 'PVP':
                        return 6;
                }

                return 3;

            case 'h5':
                switch ($this->type) {
                    case 'Big Team Battle':
                        return 8;

                    case 'Warzone':
                        return 12;
                }

                return 4;
        }
    }

    /**
     * @param $user
     *
     * @return bool
     */
    public function isAttending($user)
    {
        foreach ($this->attendees as $attendee) {
            if ($attendee->user->id == $user->id) {
                return true;
            }
        }

        return false;
    }

    public function isOver()
    {
        return $this->start->isPast();
    }

    public function isDestiny()
    {
        return $this->game == 'destiny';
    }
}
