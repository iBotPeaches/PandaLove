<?php

namespace Onyx\Overwatch\Objects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Stats.
 *
 * @property int $id
 * @property int $account_id
 * @property string $character
 * @property float $playtime
 * @property array $data
 */
class Character extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'overwatch_character_stats';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setDataAttribute(array $data)
    {
        foreach ($data as $category => $items) {
            foreach ($items as $item => $value) {
                if (Str::startsWith($item, 'overwatchguid')) {
                    unset($data[$category][$item]);
                }
            }
            ksort($data[$category]);
        }

        $this->attributes['data'] = \GuzzleHttp\json_encode($data);
    }

    public function getDataAttribute($data)
    {
        return \GuzzleHttp\json_decode($data, true);
    }

    public function getCharacterAttribute($value)
    {
        return ucfirst($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function playtimeFancy()
    {
        $value = $this->playtime;

        if ($value < 1) {
            return (60 * $value).' mins';
        }

        return $value.' hours';
    }

    public function stats()
    {
        return $this->belongsTo('Onyx\Overwatch\Objects\Stats', 'account_id', 'id');
    }

    public function image()
    {
        return asset('/images/overwatch/'.$this->getOriginal('character').'.png');
    }

    public function kd()
    {
        $kills = $this->g('general_stats.eliminations');
        $deaths = $this->g('general_stats.deaths');

        if ($deaths == 0) {
            return $kills;
        }

        return round($kills / $deaths, 2);
    }

    public function kdColor()
    {
        $kd = $this->kd();

        switch (true) {
            case $kd >= 1:
                return 'green';
            case $kd < 1:
                return 'red';
        }
    }

    public function winRate()
    {
        $win_percentage = $this->g('general_stats.win_percentage');

        return round($win_percentage * 100, 2);
    }

    public function winRateColor()
    {
        $winRate = $this->winRate();

        switch (true) {
            case $winRate >= 65:
                return 'green';

            case $winRate >= 35 && $winRate < 65:
                return 'yellow';

            case $winRate < 35:
                return 'red';
        }
    }

    public function g($key)
    {
        return array_get($this->data, $key, 0);
    }

    public function heroStats()
    {
        if (count($this->data['hero_stats']) === 0) {
            return $this->data['general_stats'];
        }

        return $this->data['hero_stats'];
    }
}
