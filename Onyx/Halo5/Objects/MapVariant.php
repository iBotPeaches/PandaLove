<?php

namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MapVariant.
 *
 * @property string $uuid
 * @property string $name
 * @property string $map_id
 * @property string $description
 * @property Map $map
 */
class MapVariant extends Model
{
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

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function isSumo() : bool
    {
        $sumoMapIds = [
            '921f5fde-e3b4-47e8-a413-5c937a54a678',
            'f9d07b11-c6d2-44e9-bc25-990e68840b3d',
        ];

        return in_array($this->uuid, $sumoMapIds);
    }

    public static function getAll()
    {
        return \Cache::remember('map_variants-metadata', 120, function () {
            $items = [];

            foreach (MapVariant::all() as $map) {
                $items[$map->uuid] = $map;
            }

            return $items;
        });
    }
}
