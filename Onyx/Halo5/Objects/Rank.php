<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rank
 * @package Onyx\Halo5\Objects
 * @property integer $level
 * @property integer $previousLevel
 * @property integer $startXp
 * @property string $uuid
 */
class Rank extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_ranks';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
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

        static::creating(function($rank)
        {
            if ($rank->level > 0)
            {
                $rank->previousLevel = ($rank->level - 1);
            }
            else
            {
                $rank->previousLevel = 0;
            }
        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------
}
