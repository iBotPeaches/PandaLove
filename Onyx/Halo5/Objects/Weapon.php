<?php namespace Onyx\Halo5\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Helpers\Date\DateHelper;

/**
 * Class Season
 * @package Onyx\Halo5\Objects
 * @property int $id
 * @property string $uuid
 * @property string $contentId
 * @property string $name
 * @property string $description
 */
class Weapon extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_weapons';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
        $all = Weapon::all();

        $rtr = [];

        foreach ($all as $item)
        {
            $rtr[$item->uuid] = $item;
        }

        return $rtr;
    }
}
