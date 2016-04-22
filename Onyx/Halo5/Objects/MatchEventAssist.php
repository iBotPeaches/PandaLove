<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Onyx\Account;
use Ramsey\Uuid\Uuid;

/**
 * Class MatchEventAssist
 * @package Onyx\Halo5\Objects
 * @property integer $id
 * @property string $match_event
 * @property Account $account_id
 */
class MatchEventAssist extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_match_event_assists';

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

    public function setAccountIdAttribute(Account $account)
    {
        $this->attributes['account_id'] = $account->id;
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function event()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\MatchEvent');
    }
    
    public function account()
    {
        return $this->hasOne('Onyx\Account', 'id', 'account_id')->select('id', 'gamertag', 'seo');
    }
}
