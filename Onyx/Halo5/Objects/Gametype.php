<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Gametype
 * @package Onyx\Halo5\Objects
 * @property string $uuid
 * @property string $contentId
 * @property string $name
 * @property string $internal_name
 * @property array $game_modes
 */
class Gametype extends Model {

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
     * Disable timestamps
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

    public function isArena()
    {
        return in_array('Arena', $this->game_modes);
    }
    
    public function getImage()
    {
        $path = public_path('images/gametypes/');

        if (file_exists($path . $this->uuid . '.png'))
        {
            return asset('images/gametypes/' . $this->uuid . '.png');
        }
        else
        {
            return asset('images/unknown-weapon.png');
        }
    }

    public static function getAll()
    {
        $all = Gametype::all();

        $rtr = [];

        foreach ($all as $item)
        {
            $rtr[$item->uuid] = $item;
        }

        return $rtr;
    }
}