<?php

namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Account;

/**
 * Class Data.
 *
 * @property int $id
 * @property int $account_id
 * @property int $membershipId
 * @property string $clanName
 * @property string $clanTag
 * @property int $glimmer
 * @property int $grimoire
 * @property int $legendary_marks
 * @property int $character_1
 * @property int $character_2
 * @property int $character_3
 * @property int $inactiveCounter
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Account $account
 */
class Data extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'destiny_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['membershipId', 'clanName', 'clanTag', 'glimmer', 'grimoire', 'character_1', 'character_2', 'character_3', 'legendary_marks', 'inactiveCounter', 'account_id'];

    public static function boot()
    {
        parent::boot();

        Account::deleting(function ($account) {
            Character::where('membershipId', $account->membershipId)->delete();
        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function getGlimmerAttribute($value)
    {
        return number_format($value);
    }

    public function getGrimoireAttribute($value)
    {
        return number_format($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function isInPandaLoveClan()
    {
        return $this->attributes['clanName'] == 'Panda Love' && $this->attributes['clanTag'] == 'WRKD';
    }

    public function characters()
    {
        return $this->hasMany('Onyx\Destiny\Objects\Character', 'membershipId', 'membershipId');
    }

    public function characterIds()
    {
        return [
            $this->character_1,
            $this->character_2,
            $this->character_3,
        ];
    }

    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }

    public function firstCharacter()
    {
        return $this->characters()->first();
    }

    public function characterAtPosition($index)
    {
        $index--;

        return $this->characters->get($index);
    }

    public function charsAbove($level = 40)
    {
        return $this->characters()->where('level', '>=', $level)->count();
    }

    public function highestLight()
    {
        return $this->characters()->orderBy('highest_light', 'desc')->first();
    }

    public function highestLevelHighestLight()
    {
        return $this->characters()->orderBy('level', 'DESC')->orderBy('highest_light', 'desc')->first();
    }

    public function charactersInOrder()
    {
        return $this->characters()->orderBy('level', 'DESC')->orderBy('highest_light', 'DESC')->get();
    }

    public function characterExists($charId)
    {
        $chars = $this->characters;

        foreach ($chars as $char) {
            if ($char->characterId == $charId) {
                return true;
            }
        }

        return false;
    }

    public function charactersCount()
    {
        return count($this->characters);
    }
}
