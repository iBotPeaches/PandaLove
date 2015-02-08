<?php namespace Onyx;

use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Helpers\String\Text;

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

    public function characters()
    {
        return $this->hasMany('Onyx\Destiny\Objects\Character', 'membershipId', 'membershipId');
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
}
