<?php

namespace Onyx\Destiny2\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Account;

/**
 * Class Data.
 *
 * @property int $id
 * @property int $account_id
 * @property int $membershipId
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
    protected $table = 'destiny2_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['membershipId', 'character_1', 'character_2', 'character_3', 'inactiveCounter', 'account_id'];

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    /**
     * @return Character[]
     */
    public function characters()
    {
        return [
            $this->character1,
            $this->character2,
            $this->character3,
        ];
    }

    /**
     * @return Character[]
     */
    public function getCharacters()
    {
        return [
            $this->character1,
            $this->character2,
            $this->character3,
        ];
    }

    public function character1()
    {
        return $this->hasOne('Onyx\Destiny2\Objects\Character', 'characterId', 'character_1');
    }

    public function character2()
    {
        return $this->hasOne('Onyx\Destiny2\Objects\Character', 'characterId', 'character_2');
    }

    public function character3()
    {
        return $this->hasOne('Onyx\Destiny2\Objects\Character', 'characterId', 'character_3');
    }

    public function characterIds()
    {
        return [
            $this->character_1,
            $this->character_2,
            $this->character_3,
        ];
    }

    public function characterAtPosition($id)
    {
        $chars = $this->getCharacters();

        if (isset($chars[$id])) {
            return $chars[$id];
        }

        return false;
    }

    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }

    public function charactersCount()
    {
        return count($this->characters);
    }
}
