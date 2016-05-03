<?php namespace Onyx\Halo5\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Helpers\Date\DateHelper;

/**
 * Class Season
 * @package Onyx\Halo5\Objects
 * @property int $id
 * @property string $contentId
 * @property string $name
 * @property Carbon $end_date
 * @property Carbon $start_date
 * @property boolean $isActive
 */
class Season extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_seasons';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $primaryKey = 'contentId';

    /**
     * Disable timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    protected $dates = ['start_date', 'end_date'];

    public static function boot()
    {
        parent::boot();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setNameAttribute($value)
    {
        if (is_null($value))
        {
            // Season name was null, so replicate the same pattern of Month Year Season
            $value = date('F Y') . ' Season';
        }
        
        $this->attributes['name'] = $value;
    }
    
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = new Carbon($value);
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = new Carbon($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function playlists()
    {
        return $this->belongsToMany('Onyx\Halo5\Objects\Playlist', 'halo5_season_playlists', 'seasonId', 'playlistId');
    }
    
    public function isFuture()
    {
        $date = $this->start_date;
        return $date->isFuture();
    }
}
