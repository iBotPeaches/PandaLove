<?php

namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Season.
 *
 * @property int $id
 * @property string $uuid
 * @property string $contentId
 * @property string $name
 * @property string $description
 */
class Weapon extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_weapons';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

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

    /**
     * @return mixed
     */
    public function getImage()
    {
        $path = public_path('uploads/h5/images/weapons/');

        if (file_exists($path.$this->uuid.'.png')) {
            return asset('uploads/h5/images/weapons/'.$this->uuid.'.png');
        } else {
            return asset('images/unknown-weapon.png');
        }
    }

    public static function getAll()
    {
        return \Cache::remember('weapons-metadata', 120, function () {
            $items = [];

            foreach (Weapon::all() as $weapon) {
                $items[$weapon->uuid] = $weapon;
            }

            return $items;
        });
    }
}
