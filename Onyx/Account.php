<?php namespace Onyx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Data as DestinyData;
use Onyx\Halo5\Objects\Data as H5Data;

/**
 * Class Account
 * @package Onyx
 * @property int $id
 * @property string $gamertag
 * @property int $accountType
 * @property string $seo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $xuid
 * @property string $destiny_membershipId
 */
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
    protected $fillable = ['gamertag', 'destiny_membershipId', 'accountType'];

    public static function boot()
    {
        parent::boot();

        Account::created(function($account)
        {
            // destiny stuff
            $data = new DestinyData();
            $data->account_id = $account->id;
            $data->save();

            // halo 5 stuff
            $h5_data = new H5Data();
            $h5_data->account_id = $account->id;
            $h5_data->save();
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

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function destiny()
    {
        return $this->hasOne('Onyx\Destiny\Objects\Data', 'account_id', 'id');
    }

    public function h5()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Data', 'account_id', 'id');
    }

    public function h5_stats()
    {
        return $this->hasMany('Onyx\Halo5\HistoricalStat', 'account_id', 'id');
    }

    public function isPandaLove()
    {
        // fallback to the new isPanda on User check
        return ($this->user instanceof User) ? $this->user->isPanda : false;
    }

    public function user()
    {
        return $this->hasOne('Onyx\User', 'account_id', 'id');
    }

    public static function getAccountIdViaDestiny($membershipId)
    {
        $account = Account::where('destiny_membershipId', $membershipId)->first();

        if ($account instanceof Account)
        {
            return $account->id;
        }

        return null;
    }
}
