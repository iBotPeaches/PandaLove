<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MapVariant
 * @package Onyx\Halo5\Objects
 * @property string $uuid
 * @property string $name
 * @property string $map_id
 * @property string $description
 * @property Map $map
 */
class MapVariant extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_map_variants';

    /**
     * The attributes that are not mass assignable.
     *
     * @var string
     */
    protected $guarded = ['uuid'];

    /**
     * The primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

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
    
    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public static function getAll()
    {
        return \Cache::remember('map_variants-metadata', 120, function()
        {
            $items = [];

            foreach (Map::all() as $map)
            {
                $items[$map->uuid] = $map;
            }

            return $items;
        });
    }
}