<?php

namespace Onyx\Calendar\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attendee.
 *
 * @property int $game_id
 * @property int $membershipId
 * @property int $characterId
 * @property int $account_id
 * @property int $user_id
 * @property bool $attended
 */
class Attendee extends Model
{
    protected $table = 'calendar_attendees';

    protected $fillable = ['game_id', 'membershipId', 'characterId', 'account_id', 'user_id', 'attended'];

    public $timestamps = false;

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // BOOT Methods
    //---------------------------------------------------------------------------------

    public static function boot()
    {
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function account()
    {
        return $this->belongsTo('Onyx\Account', 'account_id', 'id');
    }

    public function character()
    {
        return $this->belongsTo('Onyx\Destiny\Objects\Character', 'characterId', 'characterId');
    }

    public function h5()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Data', 'account_id', 'account_id');
    }

    public function user()
    {
        return $this->belongsTo('Onyx\User', 'user_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo('Onyx\Calendar\Objects\Event', 'game_id', 'id');
    }
}
