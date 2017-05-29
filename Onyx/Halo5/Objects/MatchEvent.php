<?php

namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Onyx\Account;
use Onyx\Halo5\Enums\DeathType;
use Onyx\Halo5\Enums\EventName;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Halo5\Objects\Event\Metadata;
use Onyx\Laravel\Helpers\Text;
use Ramsey\Uuid\Uuid;

/**
 * Class MatchEvent.
 *
 * @property int $id
 * @property string $game_id
 * @property int $death_owner
 * @property int $death_type
 * @property int $killer_id
 * @property int $killer_type
 * @property array $killer_attachments
 * @property int $killer_weapon_id
 * @property float $killer_x
 * @property float $killer_y
 * @property float $killer_z
 * @property int $victim_id
 * @property int $victim_type
 * @property array $victim_attachments
 * @property int $victim_stock_id
 * @property float $victim_x
 * @property float $victim_y
 * @property float $victim_z
 * @property float $distance
 * @property int $event_name
 * @property int $seconds_since_start
 * @property int $seconds_held_as_primary
 * @property int $shots_fired
 * @property int $shots_landed
 * @property int $round_index
 * @property Account $killer
 * @property Account $victim
 * @property Weapon $killer_weapon
 * @property Metadata $victim_enemy
 * @property MatchEventAssist[] $assists
 */
class MatchEvent extends Model
{
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
    protected $guarded = ['id'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($matchEvent) {
            /** @var $matchEvent MatchEvent */
            if ($matchEvent->event_name == EventName::Death) {
                $matchEvent->setDistance();
            }
        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setKillerIdAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['killer_id'] = $value->id;
        } else {
            $this->attributes['killer_id'] = null;
        }
    }

    public function setVictimIdAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['victim_id'] = $value->id;
        } else {
            $this->attributes['victim_id'] = null;
        }
    }

    public function setDeathTypeAttribute(array $event)
    {
        $fields = ['IsAssassination', 'IsGroundPound', 'IsHeadshot',  'IsMelee', 'IsShoulderBash', 'IsWeapon'];

        foreach ($fields as $field) {
            if (isset($event[$field]) && $event[$field]) {
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

    public function setSecondsHeldAsPrimaryAttribute($value)
    {
        $this->attributes['seconds_held_as_primary'] = DateHelper::returnSeconds($value);
    }

    public function setKillerWeaponIdAttribute($value)
    {
        if ($value > 0) {
            $metadata = Metadata::getAll();

            if (isset($metadata[$value])) {
                $this->attributes['killer_weapon_id'] = $value;

                return;
            }
        }

        /*
         * @url https://www.halowaypoint.com/en-us/forums/01b3ca58f06c4bd4ad074d8794d2cf86/topics/unknown-weaponid/ed7157ac-e30b-4c6d-9292-9c0032dc17c7/posts
         *
         * TLDR - 2457457776 ID is not in API
         */
        $this->attributes['killer_weapon_id'] = '3168248199'; // @todo
    }

    public function setVictimStockIdAttribute($value)
    {
        if ($value > 0) {
            $metadata = Metadata::getAll();

            if (isset($metadata[$value])) {
                $this->attributes['victim_stock_id'] = $value;

                return;
            }
        }

        /*
         * @url https://www.halowaypoint.com/en-us/forums/01b3ca58f06c4bd4ad074d8794d2cf86/topics/unknown-weaponid/ed7157ac-e30b-4c6d-9292-9c0032dc17c7/posts
         *
         * TLDR - 2457457776 ID is not in API
         */
        $this->attributes['victim_stock_id'] = '3168248199'; // @todo
    }

    public function setKillerXAttribute($value)
    {
        $this->attributes['killer_x'] = round($value, 3);
    }

    public function setKillerYAttribute($value)
    {
        $this->attributes['killer_y'] = round($value, 3);
    }

    public function setKillerZAttribute($value)
    {
        $this->attributes['killer_z'] = round($value, 3);
    }

    public function setVictimXAttribute($value)
    {
        $this->attributes['victim_x'] = round($value, 3);
    }

    public function setVictimYAttribute($value)
    {
        $this->attributes['victim_y'] = round($value, 3);
    }

    public function setVictimZAttribute($value)
    {
        $this->attributes['victim_z'] = round($value, 3);
    }

    public function setDistanceAttribute($value)
    {
        $this->attributes['distance'] = round($value, 3);
    }

    public function getKillerAttachmentsAttribute($value)
    {
        return json_decode($value);
    }

    public function getVictimAttachmentsAttribute($value)
    {
        return json_decode($value);
    }

    public function getKillerXAttribute($value)
    {
        return floatval($value);
    }

    public function getKillerYAttribute($value)
    {
        return floatval($value);
    }

    public function getKillerZAttribute($value)
    {
        return floatval($value);
    }

    public function getVictimXAttribute($value)
    {
        return floatval($value);
    }

    public function getVictimYAttribute($value)
    {
        return floatval($value);
    }

    public function getVictimZAttribute($value)
    {
        return floatval($value);
    }

    public function getDistanceAttribute($value)
    {
        return floatval($value);
    }

    public function getSecondsSinceStartAttribute($value)
    {
        if ($value == 0) {
            return '0 seconds ';
        }

        return Text::timeDuration($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function setDistance()
    {
        $x = $this->victim_x - $this->killer_x;
        $y = $this->victim_y - $this->killer_y;
        $z = $this->victim_z - $this->killer_z;

        $this->attributes['distance'] = sqrt(pow($x, 2) + pow($y, 2) + pow($z, 2));
    }

    /**
     * @param string $type
     * @param array  $data
     */
    public function setPoint($type, array $data)
    {
        $type = ($type == 'Killer' ? 'killer' : 'victim');

        $this->attributes[$type.'_x'] = floatval($data['x']);
        $this->attributes[$type.'_y'] = floatval($data['y']);
        $this->attributes[$type.'_z'] = floatval($data['z']);
    }

    /**
     * @return float
     */
    public function getPercentFired()
    {
        return round(($this->shots_landed / $this->shots_fired) * 100, 2);
    }

    /**
     * @return string
     */
    public function getKilledString()
    {
        $msg = '';
        if ($this->killer !== null) {
            $msg .= $this->killer->gamertag;
        } else {
            $msg .= 'AI';
        }
        $msg .= ' killed ';

        if ($this->victim !== null) {
            $msg .= $this->victim->gamertag;
        } else {
            if ($this->victim_enemy instanceof Metadata) {
                $msg .= 'a '.$this->victim_enemy->name;
            } else {
                $msg .= 'Unknown Enemy';
            }
        }

        $msg .= ' with a '.$this->killer_weapon->name;
        $msg .= ' ('.$this->distance.'m away)';

        if (count($this->assists) > 0) {
            $msg .= ' assisted by: ';
            foreach ($this->assists as $assist) {
                $msg .= $assist->account->gamertag.', ';
            }
        }

        return rtrim($msg, ', ');
    }

    public function match()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Match', 'game_id', 'id');
    }

    public function assists()
    {
        return $this->hasMany('Onyx\Halo5\Objects\MatchEventAssist', 'match_event', 'id');
    }

    public function killer()
    {
        return $this->hasOne('Onyx\Account', 'id', 'killer_id')->select('gamertag', 'id', 'seo');
    }

    public function victim()
    {
        return $this->hasOne('Onyx\Account', 'id', 'victim_id')->select('gamertag', 'id', 'seo');
    }

    public function killer_weapon()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Event\Metadata', 'killer_weapon_id', 'uuid');
    }

    public function victim_enemy()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Event\Metadata', 'uuid', 'victim_stock_id');
    }
}
