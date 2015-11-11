<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Helpers\Date\DateHelper;

class PlaylistData extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_playlists_data';

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

    public function setHighestRankAttribute($value)
    {
        $this->attributes['highest_rank'] = (is_null($value) ? 0 : $value);
    }

    public function setCurrentRankAttribute($value)
    {
        $this->attributes['current_rank'] = (is_null($value) ? 0 : $value);
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

    public function h5()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Data', 'account_id', 'account_id');
    }
}
