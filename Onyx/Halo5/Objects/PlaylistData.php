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

    public function stock()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\Playlist', 'playlistId', 'contentId');
    }

    public function high_csr()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\CSR', 'highest_CsrDesignationId', 'designationId');
    }

    public function current_csr()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\CSR', 'current_CsrDesignationId', 'designationId');
    }

    public function getGamesDone()
    {
        return 10 - $this->measurementMatchesLeft;
    }

    public function tier($type = 'highest')
    {
        if ($type == "highest")
        {
            if ($this->measurementMatchesLeft != 0)
            {
                return (10 - $this->measurementMatchesLeft);
            }

            return $this->highest_CsrTier;
        }
        else
        {
            if ($this->measurementMatchesLeft != 0)
            {
                return (10 - $this->measurementMatchesLeft);
            }

            return $this->current_CsrTier;
        }
    }

    public function title($type = 'highest')
    {
        $action = $type == 'highest' ? 'high_csr' : 'current_csr';
        $tier = $type == 'highest' ? 'highest_CsrTier' : 'current_CsrTier';

        switch ($this->designationId)
        {
            case 0: // Unranked
            case 6: // SemiPro
            case 7: // Pro
                return $this->$action->name;

            default:
                return $this->$action->name . " " . $this->$tier;
        }
    }

    public function rank($type = 'highest')
    {
        $action = $type == 'highest' ? 'highest_rank' : 'current_rank';

        switch ($this->$action)
        {
            case 1:
                return '1st';

            case 2:
                return '2nd';

            case 3:
                return '3rd';

            default:
                return $this->$action . 'th';
        }
    }
}
