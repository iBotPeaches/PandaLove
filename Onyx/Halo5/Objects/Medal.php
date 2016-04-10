<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Medal
 * @package Onyx\Halo5\Objects
 * @property int $id
 * @property string $contentId
 * @property string $name
 * @property string $description
 * @property string classification
 * @property int $difficulty
 */
class Medal extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_medals';

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

    public function setDifficultyAttribute($value)
    {
        $this->attributes['difficulty'] = intval($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public static function getAll()
    {
        return \Cache::remember('medals-metadata', 120, function()
        {
            $items = [];

            foreach (Medal::all() as $medal)
            {
                $items[$medal->contentId] = $medal;
            }

            return $items;
        });
    }
}
