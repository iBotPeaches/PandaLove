<?php

namespace Onyx\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Account;
use Onyx\Destiny\Objects\Game;
use Onyx\Halo5\Objects\Data;

/**
 * Class Comment.
 *
 * @property int $id
 * @property string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $destiny_membershipId
 * @property string $destiny_characterId
 * @property int $commentable_id
 * @property string $commentable_type
 * @property int $parent_comment_id
 * @property int $account_id
 */
class Comment extends Model
{
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

        if ($date->diffInDays() > 30) {
            return $date->format('M j, Y - g:ma');
        } else {
            return $date->diffForHumans();
        }
    }

    /**
     * @param $value Account|null
     */
    public function setDestinyCharacterIdAttribute($value)
    {
        if ($value instanceof Account) {
            $this->attributes['destiny_characterId'] = $value->characterId;
        } else {
            $this->attributes['destiny_characterId'] = null;
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
        return $this->hasOne('Onyx\Destiny\Objects\GamePlayer', 'characterId', 'destiny_characterId');
    }

    public function account()
    {
        return $this->hasOne('Onyx\Account', 'id', 'account_id');
    }

    public function destiny()
    {
        return $this->hasOne('Onyx\Destiny\Objects\Data', 'membershipId', 'destiny_membershipId');
    }

    public function emblem()
    {
        if ($this->commentable instanceof Game) {
            if ($this->destiny_characterId != null) { // In Game
                return $this->player->emblem->extra;
            } else {
                return $this->destiny->characterAtPosition(1)->emblem->extra;
            }
        } elseif ($this->commentable instanceof Data) {
            return false;
        }
    }
}
