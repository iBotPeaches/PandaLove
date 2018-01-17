<?php

namespace Onyx\Fortnite\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Stats
 * @package Onyx\Fortnite\Objects
 */
class Stats extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fortnite_stats';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


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

    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }
}
