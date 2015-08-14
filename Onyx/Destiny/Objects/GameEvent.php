<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model {

    protected $table = 'game_events';

    protected $fillable = ['title'];

    protected $dates = ['start'];

    public $timestamps = true;

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // BOOT Methods
    //---------------------------------------------------------------------------------

    public static function boot()
    {
        GameEvent::creating(function ($event)
        {
            if ($event->max_players == 0)
            {
                $event->max_players = $event->getPlayerDefaultSize($event->type);
            }
        });
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function humanDate()
    {
        return $this->start->format('F j - g:ia');
    }

    public function count()
    {
        return count($this->attendees);
    }

    public function attendees()
    {
        return $this->hasMany('Onyx\Destiny\Objects\Attendee', 'game_id', 'id');
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
        switch ($this->type)
        {
            case "ToO":
                return '#000';

            case "Raid":
            case "Flawless":
                return '#5BBD72';

            case "PoE":
                return '#564F8A';

            case "PVP":
                return '#D95C5C';
        }
    }

    public function getPlayerDefaultSize($type)
    {
        switch ($type)
        {
            case "ToO":
            case "PoE":
                return 3;

            case "Raid":
            case "Flawless":
            case "PVP":
                return 6;
        }
    }
}