<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Impulse
 * @package Onyx\Halo5\Objects
 * @property string $id
 * @property string $contentId
 * @property string $name
 */
class Impulse extends Model {

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

}