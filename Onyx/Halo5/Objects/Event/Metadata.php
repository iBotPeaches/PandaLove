<?php namespace Onyx\Halo5\Objects\Event;

use Illuminate\Database\Eloquent\Model;
use Onyx\Halo5\Enums\MetadataType;
use Ramsey\Uuid\Uuid;

/**
 * Class Vehicle
 * @package Onyx\Halo5\Objects
 * @property string $uuid
 * @property Uuid $contentId
 * @property string $name
 * @property string $description
 * @property integer $type
 */
class Metadata extends Model {

    /**
     * Spartan UUID
     */
    const SPARTAN_UUID = '3168248199';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_event_metadata';

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

    public static function boot()
    {
        parent::boot();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function getNameAttribute($value)
    {
        switch ($this->uuid)
        {
            case self::SPARTAN_UUID:
                return 'Melee';

            default:
                return $value;
        }
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function getImage($size = 'small')
    {
        $path = null;

        switch ($this->type)
        {
            case MetadataType::Enemy:
                $path = 'enemies';
                $size = ($size == 'small' ? '-small' : '-large');
                break;

            case MetadataType::Vehicle:
                $path = 'vehicles';
                $size = ($size == 'small' ? '-small' : '-large');
                break;

            case MetadataType::Weapon:
                $path = 'weapons';
                $size = null;
                break;
        }

        return asset('images/' . $path . '/' . $this->uuid . $size . '.png');
    }

    public static function getAll()
    {
        return \Cache::remember('metadata-metadata', 120, function()
        {
            $items = [];

            foreach (Metadata::all() as $metadata)
            {
                $items[$metadata->uuid] = $metadata;
            }

            return $items;
        });
    }
}
