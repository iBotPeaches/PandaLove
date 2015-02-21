<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Account;

class Comment extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['comment', 'membershipId', 'characterId', 'parent_comment_id'];

    /**
     * @var bool
     */
    public $timestamps = true;

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function getCreatedAtAttribute($value)
    {
        $date = new Carbon($value);
        $date = $date->timezone('America/Chicago');

        if ($date->diffInDays() > 30)
        {
            return $date->format('M j, Y - g:ma');
        }
        else
        {
            return $date->diffForHumans();
        }
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function commentable()
    {
        return $this->morphTo();
    }

    public function player()
    {
        return $this->hasOne('Onyx\Destiny\Objects\GamePlayer', 'characterId', 'characterId');
    }

    public function account()
    {
        return $this->hasOne('Onyx\Account', 'membershipId', 'membershipId');
    }
}
