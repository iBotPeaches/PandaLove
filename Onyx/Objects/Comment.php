<?php namespace Onyx\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Account;

/**
 * Class Comment
 * @package Onyx\Objects
 * @property integer $id
 * @property string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $membershipId
 * @property string $characterId
 * @property integer $commentable_id
 * @property string $commentable_type
 * @property integer $parent_comment_id
 * @property integer $account_id
 */
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
        return $this->hasOne('Onyx\Account', 'id', 'account_id');
    }

    public function destiny()
    {
        return $this->hasOne('Onyx\Destiny\Objects\Data', 'membershipId', 'membershipId');
    }
}
