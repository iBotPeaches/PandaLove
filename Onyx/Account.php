<?php namespace Onyx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Character;
use Onyx\Destiny\Objects\Data;

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
    }

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

    public function destiny()
    {
        return $this->hasOne('Onyx\Destiny\Objects\Data', 'account_id', 'id');
    }

    public function isPandaLove()
    {
        // actual check validates the Clan information from Destiny
        // for fallback, lets just call this.
        return $this->destiny->isPandaLove();
    }

    public function user()
    {
        return $this->belongsTo('Onyx\User');
    }

    public static function getAccountIdViaDestiny($membershipId)
    {
        $data = Data::where('membershipId', $membershipId)->first();

        if ($data instanceof Data)
        {
            return $data->account_id;
        }

        return null;
    }
}
