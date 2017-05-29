<?php

namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Impulse.
 *
 * @property string $id
 * @property string $contentId
 * @property string $name
 */
class Impulse extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_impulses';

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
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public static function getAll()
    {
        return \Cache::remember('impulses-metadata', 120, function () {
            $items = [];

            foreach (Impulse::all() as $impulse) {
                $items[$impulse->id] = $impulse;
            }

            return $items;
        });
    }
}
