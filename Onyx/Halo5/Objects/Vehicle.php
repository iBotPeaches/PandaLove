<?php namespace Onyx\Halo5\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Ramsey\Uuid\Uuid;

/**
 * Class Vehicle
 * @package Onyx\Halo5\Objects
 * @property string $uuid
 * @property Uuid $contentId
 * @property string $name
 * @property string $description
 * @property boolean $useableByPlayer
 */
class Vehicle extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_vehicles';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['uuid'];

    /**
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
        return \Cache::remember('vehicles-metadata', 120, function()
        {
            $items = [];

            foreach (Vehicle::all() as $vehicle)
            {
                $items[$vehicle->uuid] = $vehicle;
            }

            return $items;
        });
    }
}
