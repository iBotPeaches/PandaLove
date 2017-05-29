<?php

namespace Onyx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Enums\Console;
use Onyx\Destiny\Helpers\String\Text;
use Onyx\Destiny\Objects\Data;
use Onyx\Halo5\Objects\HistoricalStat;

/**
 * Class Account.
 *
 * @property int $id
 * @property string $gamertag
 * @property int $accountType
 * @property string $seo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $xuid
 * @property string $destiny_membershipId
 * @property Data $destiny
 * @property \Onyx\Halo5\Objects\Data $h5
 */
class Account extends Model
{
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

    /**
     * @return Data
     */
    public function destiny()
    {
        return $this->hasOne('Onyx\Destiny\Objects\Data', 'account_id', 'id');
    }

    /**
     * @return \Onyx\Halo5\Objects\Data
     */
    public function h5()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Data', 'account_id', 'id');
    }

    /**
     * @return \Onyx\Halo5\Objects\Data
     */
    public function h5_emblem()
    {
        return $this->h5()->select('id', 'account_id');
    }

    /**
     * @return HistoricalStat
     */
    public function h5_stats()
    {
        return $this->hasMany('Onyx\Halo5\HistoricalStat', 'account_id', 'id');
    }

    /**
     * @return bool
     */
    public function isPandaLove()
    {
        // fallback to the new isPanda on User check
        return ($this->user instanceof User) ? $this->user->isPanda : false;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->hasOne('Onyx\User', 'account_id', 'id');
    }

    /**
     * @param $membershipId
     *
     * @return int|null
     */
    public static function getAccountIdViaDestiny($membershipId)
    {
        $account = self::where('destiny_membershipId', $membershipId)->first();

        if ($account instanceof self) {
            return $account->id;
        }
    }

    /**
     * @return string
     */
    public function console()
    {
        return $this->accountType == Console::Xbox ? 'Xbox' : 'Playstation';
    }

    /**
     * @return string
     */
    public function sConsole()
    {
        return $this->accountType == Console::Xbox ? 'Xbox' : 'PSN';
    }

    /**
     * @return string
     */
    public function console_image()
    {
        return asset('images/'.(($this->accountType == Console::Xbox) ? 'xbl.png' : 'psn.png'));
    }

    /**
     * @return string
     */
    public function color()
    {
        return $this->accountType == Console::Xbox ? 'green' : 'blue';
    }
}
