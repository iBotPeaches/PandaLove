<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;
use Onyx\Account;
use Onyx\Destiny\Enums\Console;
use Onyx\Destiny\Helpers\String\Text as DestinyText;
use Onyx\Halo5\Client;
use Onyx\Halo5\CustomTraits\Stats;
use Onyx\Halo5\Helpers\Date\DateHelper;
use Onyx\Halo5\Helpers\String\Text as Halo5Text;
use Onyx\Laravel\Helpers\Text as LaravelText;
use Ramsey\Uuid\Uuid;

/**
 * Class MatchPlayer
 * @package Onyx\Halo5\Objects
 *
 * @property string $uuid
 * @property integer $account_id
 * @property string $team_id
 * @property string $game_id
 * @property array $killed
 * @property array $killed_by
 * @property array $medals
 * @property array $enemies
 * @property array $weapons
 * @property array $impulses
 * @property integer $warzone_req
 * @property integer $total_pies
 * @property integer $rank
 * @property boolean $dnf
 * @property integer $avg_lifestime
 * @property integer $totalKills
 * @property integer $totalSpartanKills
 * @property integer $totalAiKills
 * @property integer $totalHeadshots
 * @property integer $totalDeaths
 * @property integer $totalAssists
 * @property integer $totalTimePlayed
 * @property float $weapon_dmg
 * @property integer $shots_fired
 * @property integer $shots_landed
 * @property integer $totalMeleeKills
 * @property integer $totalAssassinations
 * @property integer $totalGroundPounds
 * @property integer $totalShoulderBash
 * @property integer $totalGrenadeKills
 * @property integer $totalPowerWeaponKills
 * @property integer $totalPowerWeaponTime
 * @property integer $spartanRank
 * @property integer $CsrTier
 * @property integer $CsrDesignationId
 * @property integer $Csr
 * @property integer $percentNext
 * @property integer $ChampionRank
 *
 * @property MatchTeam $matchTeam
 * @property Account $account
 * @property CSR $csr
 */
class MatchPlayer extends Model {

    use Stats;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_matches_players';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['uuid'];

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * Disable timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($player)
        {
            /* @var $player $this */
            $player->totalAiKills = $player->totalKills - $player->totalSpartanKills;
        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setKilledAttribute($value)
    {
        if (is_array($value))
        {
            $inserted = [];
            $deferred = [];
            $deferred_gts = '';

            foreach ($value as $opponent)
            {
                $account = Account::where('seo', DestinyText::seoGamertag($opponent['GamerTag']))
                    ->where('accountType', Console::Xbox)
                    ->first();

                if ($account instanceof Account)
                {
                    $inserted[$account->id] = $opponent['TotalKills'];
                }
                else
                {
                    $deferred[]  = $opponent;
                    $deferred_gts .= Halo5Text::encodeGamertagForApi($opponent['GamerTag']) . ",";
                }
            }

            if (count($deferred) > 0)
            {
                // Now lets go through the ones we deferred and load their service records (thus making accounts)
                $client = new Client();
                $client->getAccountsByGamertags(rtrim($deferred_gts, ","));

                foreach ($deferred as $opponent)
                {
                    $account = Account::where('seo', DestinyText::seoGamertag($opponent['GamerTag']))
                        ->where('accountType', Console::Xbox)
                        ->first();

                    if ($account instanceof Account)
                    {
                        $inserted[$account->id] = $opponent['TotalKills'];
                    }
                }
            }

            $this->attributes['killed'] = json_encode($inserted);
        }
    }

    public function setKilledByAttribute($value)
    {
        if (is_array($value))
        {
            $inserted = [];
            $deferred = [];
            $deferred_gts = '';

            foreach ($value as $opponent)
            {
                $account = $this->_getAccount($opponent['GamerTag']);

                if ($account instanceof Account)
                {
                    $inserted[$account->id] = $opponent['TotalKills'];
                }
                else
                {
                    $deferred[]  = $opponent;
                    $deferred_gts .= Halo5Text::encodeGamertagForApi($opponent['GamerTag']) . ",";
                }
            }

            if (count($deferred) > 0)
            {
                // Now lets go through the ones we deferred and load their service records (thus making accounts)
                $client = new Client();
                $client->getAccountsByGamertags(rtrim($deferred_gts, ","));

                foreach ($deferred as $opponent)
                {
                    $account = $this->_getAccount($opponent['GamerTag']);

                    if ($account instanceof Account)
                    {
                        $inserted[$account->id] = $opponent['TotalKills'];
                    }
                }
            }

            $this->attributes['killed_by'] = json_encode($inserted);
        }
    }

    public function setMedalsAttribute($value)
    {
        if (is_array($value))
        {
            $insert = [];

            foreach($value as $medal)
            {
                $insert[$medal['MedalId']] = $medal['Count'];
            }

            $this->attributes['medals'] = json_encode($insert);
        }
    }

    public function setWeaponsAttribute($value)
    {
        if (is_array($value))
        {
            $insert = [];

            foreach($value as $weapon)
            {
                if ($weapon['TotalKills'] > 0)
                {
                    $insert[$weapon['WeaponId']['StockId']] = $weapon['TotalKills'];
                }
            }

            arsort($insert);
            $this->attributes['weapons'] = json_encode($insert);
        }
    }

    public function setEnemiesAttribute($value)
    {
        if (is_array($value))
        {
            $insert = [];

            foreach($value as $enemy)
            {
                $insert[$enemy['Enemy']['BaseId']] = $enemy['TotalKills'];
            }

            arsort($insert);
            $this->attributes['enemies'] = json_encode($insert);
        }
    }

    public function setImpulsesAttribute($value)
    {
        if (is_array($value))
        {
            $insert = [];

            foreach($value as $impulse)
            {
                $insert[$impulse['Id']] = $impulse['Count'];
            }

            arsort($insert);
            $this->attributes['impulses'] = json_encode($insert);
        }
    }

    public function setTotalTimePlayedAttribute($value)
    {
        $this->attributes['totalTimePlayed'] = DateHelper::returnSeconds($value);
    }

    public function setAvgLifestimeAttribute($value)
    {
        $this->attributes['avg_lifestime'] = DateHelper::returnSeconds($value);
    }

    public function setTotalPowerWeaponTimeAttribute($value)
    {
        if ($value != "PT0S")
        {
            $this->attributes['totalPowerWeaponTime'] = DateHelper::returnSeconds($value);
        }
        else
        {
            $this->attributes['totalPowerWeaponTime'] = 0;
        }
    }

    public function setAccountIdAttribute($value)
    {
        if ($value instanceof Account)
        {
            $this->attributes['account_id'] = $value->id;
        }
    }
    
    public function setUuidAttribute($value)
    {
        if ($value instanceof Uuid)
        {
            $this->attributes['uuid'] = $value->toString();
        }
    }
    
    public function getKilledAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getKilledByAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getMedalsAttribute($value)
    {
        $medals = json_decode($value, true);

        if (is_array($medals))
        {
            $stockMedals = Medal::getAll();

            foreach($medals as $key => &$value)
            {
                if (isset($stockMedals[$key]))
                {
                    $medal = $stockMedals[$key];
                    $medal['count'] = $value;
                    $value = $medal;
                }
                else
                {
                    unset($value);
                }
            }
        }
        return $medals;
    }

    public function getWeaponsAttribute($value)
    {
        $weapons = json_decode($value, true);

        if (is_array($weapons))
        {
            $stockWeapons = Weapon::getAll();

            foreach ($weapons as $key => &$value)
            {
                if (isset($stockWeapons[$key]))
                {
                    $weapon = $stockWeapons[$key];
                    $weapon['count'] = $value;
                    $value = $weapon;
                }
                else
                {
                    unset($value);
                }
            }
        }
        return $weapons;
    }

    public function getEnemiesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getImpulsesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getWeaponDmgAttribute($value)
    {
        return floatval($value);
    }

    public function getChampionRank($value)
    {
        switch ($value)
        {
            case 1:
                return '1st';

            case 2:
                return '2nd';

            case 3:
                return '3rd';

            default:
                return $value . 'th';
        }
    }

    public function getRankAttribute($value)
    {
        switch ($value)
        {
            case 1:
                return '1<sup>st</sup>';

            case 2:
                return '2<sup>nd</sup>';

            case 3:
                return '3<sup>rd</sup>';

            default:
                return $value . '<sup>th</sup>';
        }
    }


    public function getAvgLifestimeAttribute($value)
    {
        return LaravelText::timeDuration($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function account()
    {
        return $this->hasOne('Onyx\Account', 'id', 'account_id')->select('id', 'gamertag', 'seo');
    }

    public function csr()
    {
        return $this->belongsTo('Onyx\Halo5\Objects\CSR', 'CsrDesignationId', 'designationId');
    }

    public function team()
    {
        return $this->hasOne('Onyx\Halo5\Objects\MatchTeam', 'key', 'team_id');
    }

    //---------------------------------------------------------------------------------
    // Private Methods - Helper
    //---------------------------------------------------------------------------------

    public function getArenaImage()
    {
        $csr = $this->csr;

        if ($csr != null)
        {
            return $csr->tiers->{$this->CsrTier};
        }
    }
    
    public function getArenaTooltip()
    {
        $csr = $this->csr;
        
        switch ($csr->designationId)
        {
            case 0:
                return 'Unranked ( ' . (10 - $this->CsrTier) . ' games remaining)';

            case 6:
                return $csr->name . ' CSR: ' . $this->Csr;

            case 7:
                return $csr->name . ' Rank: ' . $this->ChampionRank;

            default:
                return $csr->name . ' ' . $this->CsrTier;
        }
    }

    public function kd($formatted = true)
    {
        return self::stat_kd($this->totalKills, $this->totalDeaths, $formatted);
    }

    public function kad($formatted = true)
    {
        return self::stat_kad($this->totalKills, $this->totalDeaths, $this->totalAssists, $formatted);
    }

    /**
     * @param $gamertag
     * @return mixed
     */
    private function _getAccount($gamertag)
    {
        return \Cache::remember('gamertag-' . $gamertag, 60, function() use ($gamertag)
        {
            return Account::where('seo', DestinyText::seoGamertag($gamertag))
                ->where('accountType', Console::Xbox)
                ->first();
        });
    }
}