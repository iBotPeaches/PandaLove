<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model {

    protected $table = 'game_events';

    protected $fillable = ['title'];

    public $timestamps = true;

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

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
}