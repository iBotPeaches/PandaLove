<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CSR
 * @package Onyx\Halo5\Objects
 * @property int $id
 * @property string $name
 * @property string bannerUrl
 * @property array $tiers
 * @property int designationId
 */
class CSR extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_csrs';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Disable timestamps
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

    public function setTiersAttribute($tiers)
    {
        $set = [];
        foreach($tiers as $tier)
        {
            $set[intval($tier['id'])] = $tier['iconImageUrl'];
        }

        $this->attributes['tiers'] = json_encode($set, JSON_FORCE_OBJECT);
    }

    public function getTiersAttribute($value)
    {
        return json_decode($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

}
