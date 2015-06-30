<?php namespace Onyx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Character;

class Account extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['gamertag', 'membershipId', 'accountType'];

    public static function boot()
    {
        parent::boot();

        Account::deleting(function($account)
        {
            Character::where('membershipId', $account->membershipId)->delete();
        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setGamertagAttribute($value)
    {
        $this->attributes['gamertag'] = $value;
        $this->attributes['seo'] = Text::seoGamertag($value);
    }

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

    public function isPandaLove()
    {
        return $this->attributes['clanName'] == "Panda Love" && $this->attributes['clanTag'] == "WRKD";
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
            $this->character_3
        ];
    }

    public function user()
    {
        return $this->belongsTo('Onyx\User');
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

    public function charsAbove($level = 30)
    {
        return $this->characters()->where('level', '>=', $level)->count();
    }

    public function characterExists($charId)
    {
        $chars = $this->characters;

        foreach($chars as $char)
        {
            if ($char->characterId == $charId)
            {
                return true;
            }
        }

        return false;
    }
}
