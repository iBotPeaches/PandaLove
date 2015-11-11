<?php namespace Onyx\Halo5\Objects;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Halo5\Helpers\Date\DateIntervalFractions;

class Data extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_data';

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

    public function setMedalsAttribute($value)
    {
        if (is_array($value))
        {
            $this->attributes['medals'] = json_encode($value);
        }
    }

    public function setTotalTimePlayedAttribute($value)
    {
        $this->attributes['totalTimePlayed'] = DateHelper::returnSeconds($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function account()
    {
        return $this->belongsTo('Onyx\Account', 'id', 'account_id');
    }

    public function playlists()
    {
        return $this->hasMany('Onyx\Halo5\Objects\PlaylistData', 'account_id', 'account_id');
    }

}
