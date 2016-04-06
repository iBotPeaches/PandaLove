<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

class Map extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_maps';

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

    public function getGameModesAttribute()
    {
        return json_decode($this->game_modes);
    }

    public function setGameModesAttribute($modes)
    {
        $this->game_modes = json_encode($modes);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function getImage()
    {
        $path = 'public/images/maps/';

        if (! file_exists($path . $this->uuid . '.jpg'))
        {
            return asset('images/' . $this->uuid . '.jpg');
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