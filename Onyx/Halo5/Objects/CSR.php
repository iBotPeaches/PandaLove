<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

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
            $set[] = array(
                'icon' => $tier['iconImageUrl'],
                'id' => $tier['id']
            );
        }

        $this->attributes['tiers'] = json_encode($set);
    }

    public function getTiersAttribute($value)
    {
        return $value;
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

}
