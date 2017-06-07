<?php

namespace Onyx\Overwatch\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Stats
 * @property int $id
 * @property int $account_id
 * @property string $character
 * @property float $playtime
 * @property array $data
 * @package Onyx\Overwatch\Objects
 */
class Character extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'overwatch_character_stats';

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

    public function setDataAttribute(array $data)
    {
        $this->attributes['data'] = \GuzzleHttp\json_encode($data);
    }

    public function getDataAttribute($data)
    {
        return \GuzzleHttp\json_decode($data);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function stats()
    {
        return $this->belongsTo('Onyx\Overwatch\Objects\Stats', 'account_id', 'id');
    }

    public function image()
    {
        return asset('/images/overwatch/' . $this->character . '.png');
    }

}
