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

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function characters()
    {
        return $this->hasMany('Onyx\Destiny\Objects\Character', 'characterId', 'characterId');
    }

    public function user()
    {
        return $this->belongsTo('Onyx\User');
    }
}
