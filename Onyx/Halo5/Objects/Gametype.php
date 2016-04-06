<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

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

    public function getGameModesAttribute()
    {
        return json_decode($this->game_modes);
    }

    public function setGameModesAttribute($modes)
    {
        $this->attributes['game_modes'] = json_encode($modes);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function getImage()
    {
        $path = 'public/images/gametypes/';

        if (! file_exists($path . $this->uuid . '.png'))
        {
            return asset('images/' . $this->uuid . '.png');
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