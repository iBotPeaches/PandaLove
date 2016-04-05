<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Onyx\Account;
use Onyx\Halo5\Enums\DeathType;
use Onyx\Halo5\Enums\EventName;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Ramsey\Uuid\Uuid;

/**
 * Class MatchEvent
 * @package Onyx\Halo5\Objects
 * @property string $uuid
 * @property string $game_id
 * @property integer $death_owner
 * @property integer $death_type
 * @property Account $killer
 * @property integer $killer_type
 * @property array $killer_attachments
 * @property Weapon $killer_weapon
 * @property double $killer_x
 * @property double $killer_y
 * @property double $killer_z
 * @property Account $victim
 * @property integer $victim_type
 * @property array $victim_attachments
 * @property Weapon $victim_weapon
 * @property double $victim_x
 * @property double $victim_y
 * @property double $victim_z
 * @property double $distance
 * @property integer $event_name
 * @property integer $seconds_since_start
 */
class MatchEvent extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_match_events';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['uuid'];

    /**
     * Disable timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($matchEvent)
        {
            $matchEvent->uuid = Uuid::uuid4();
            $matchEvent->setDistance();
        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setKillerAttribute(Account $killer)
    {
        $this->attributes['killer'] = $killer->id;
    }

    public function setVictimAttribute(Account $victim)
    {
        $this->attributes['victim'] = $victim->id;
    }

    public function setDeathTypeAttribute(array $event)
    {
        $fields = ['IsAssassination', 'IsGroundPound', 'IsHeadshot',  'IsMelee', 'IsShoulderBash', 'IsWeapon'];

        foreach ($fields as $field)
        {
            if (isset($event[$field]) && $event[$field])
            {
                $this->attributes['death_type'] = DeathType::getId($field);
                break;
            }
        }
    }

    public function setKillerAttachmentsAttribute(array $value)
    {
        $this->attributes['killer_attachments'] = json_encode($value);
    }

    public function setVictimAttachmentsAttribute(array $value)
    {
        $this->attributes['victim_attachments'] = json_encode($value);
    }

    public function setEventNameAttribute($value)
    {
        $this->attributes['event_name'] = EventName::getId($value);
    }

    public function setSecondsSinceStartAttribute($value)
    {
        $this->attributes['seconds_since_start'] = DateHelper::returnSeconds($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    /**
     *
     */
    public function setDistance()
    {
        $x = abs($this->victim_x - $this->killer_x);
        $y = abs($this->victim_y - $this->killer_y);
        $z = abs($this->victim_z - $this->killer_z);

        $this->attributes['distance'] = sqrt(($x ^ 2) + ($y ^ 2) + ($z ^ 2));
    }

    /**
     * @param string $type
     * @param array $data
     */
    public function setPoint($type = 'Killer', array $data)
    {
        $type = ($type == 'Killer' ? 'killer' : 'victim');

        $this->attributes[$type . "_x"] = floatval($data['x']);
        $this->attributes[$type . "_y"] = floatval($data['y']);
        $this->attributes[$type . "_z"] = floatval($data['z']);
    }

    public function match()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Match');
    }

    public function assists()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchEventAssist', 'match_event', 'uuid');
    }

    public function killer_weapon()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Weapon', 'uuid', 'killer_weapon');
    }

    public function victim_weapon()
    {
        return $this->belongsTo('Onyx\Halo\Objects\Weapon', 'uuid', 'victim_weapon');
    }
}
