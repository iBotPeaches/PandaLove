<?php

namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Gametype.
 *
 * @property string $uuid
 * @property string $contentId
 * @property string $name
 * @property string $internal_name
 * @property array $game_modes
 */
class Gametype extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_gametypes';

    /**
     * The attributes that are not mass assignable.
     *
     * @var string
     */
    protected $guarded = ['uuid'];

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * Disable timestamps.
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

    public function getGameModesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setGameModesAttribute($modes)
    {
        $this->attributes['game_modes'] = json_encode($modes);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function isBreakout()
    {
        return $this->contentId == '1e473914-46e4-408d-af26-178fb115de76';
    }

    public function isWarzoneFirefight()
    {
        return $this->contentId == 'dfd51ee3-9060-46c3-b131-08d946c4c7b9';
    }

    public function isInfection()
    {
        return $this->contentId == 'f6051f51-bbb6-4ccc-8ac0-cf42b7291c76';
    }

    public function getImage()
    {
        $path = public_path('images/gametypes/');

        if (file_exists($path.$this->uuid.'.png')) {
            return asset('images/gametypes/'.$this->uuid.'.png');
        } else {
            return asset('images/unknown-weapon.png');
        }
    }

    public static function getAll()
    {
        return \Cache::remember('gametypes-metadata', 120, function () {
            $items = [];

            foreach (Gametype::all() as $gametype) {
                $items[$gametype->uuid] = $gametype;
            }

            return $items;
        });
    }
}
