<?php

namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Map.
 *
 * @property string $uuid
 * @property string $contentId
 * @property string $name
 * @property string $description
 * @property array $game_modes
 */
class Map extends Model
{
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
     * The primary key of the table.
     *
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

    public function getImage()
    {
        $path = public_path('uploads/h5/images/maps/');

        if (file_exists($path.$this->uuid.'.jpg')) {
            return asset('uploads/h5/images/maps/'.$this->uuid.'.jpg');
        } else {
            return asset('images/unknown-weapon.png');
        }
    }

    public static function getAll()
    {
        return \Cache::remember('maps-metadata', 120, function () {
            $items = [];

            foreach (Map::all() as $map) {
                $items[$map->uuid] = $map;
            }

            return $items;
        });
    }
}
