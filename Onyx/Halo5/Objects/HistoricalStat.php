<?php

namespace Onyx\Halo5\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HistoricalStat.
 *
 * @property int $id
 * @property int $account_id
 * @property float $arena_kd
 * @property float $arena_kda
 * @property int $arena_total_games
 * @property float $warzone_kd
 * @property float $warzone_kda
 * @property int $warzone_total_games
 * @property Carbon $date
 */
class HistoricalStat extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_stats_history';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $date = ['date'];

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

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------
    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }
}
