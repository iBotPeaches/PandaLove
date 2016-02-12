<?php namespace Onyx\Destiny\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Hash
 * @package Onyx\Destiny\Objects
 * @property int $id
 * @property string $hash
 * @property string $description
 * @property string $extra
 * @property string $extraSecondary
 * @property string $extraThird
 */
class Hash extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'destiny_metadata';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hash', 'title', 'description', 'extra', 'extraSecondary'];

    /**
     * @var bool
     */
    public $timestamps = false;

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function getExtraAttribute($value)
    {
        $live_path = 'uploads/thumbs/';
        $location = public_path($live_path);
        $filename = $this->hash . "." . pathinfo($value, PATHINFO_EXTENSION);

        if (\File::isFile($location . $filename))
        {
            return asset($live_path . $filename);
        }

        return $value;
    }

    public function getExtraSecondaryAttribute($value)
    {
        $live_path = 'uploads/thumbs/';
        $location = public_path($live_path);
        $filename = $this->hash . "_bg" . "." . pathinfo($value, PATHINFO_EXTENSION);

        if (\File::isFile($location . $filename))
        {
            return asset($live_path . $filename);
        }

        return $value;
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public static function loadHashesFromApi($data)
    {
        self::loadDefinitions($data, 'buckets', 'bucketHash', 'bucketName', 'bucketDescription');
        self::loadDefinitions($data, 'stats', 'statHash', 'statName', 'statDescription');
        self::loadDefinitions($data, 'items', 'itemHash', 'itemName', 'itemDescription', 'icon', 'secondaryIcon');
        self::loadDefinitions($data, 'activities', 'activityHash', 'activityName', 'activityDescription', 'icon', 'pgcrImage', 'activityLevel');
        self::loadDefinitions($data, 'classes', 'classHash', 'className', '');
        self::loadDefinitions($data, 'genders', 'genderHash', 'genderName', 'genderType');
        self::loadDefinitions($data, 'races', 'raceHash', 'raceName', 'raceDescription');
        self::loadDefinitions($data, 'destinations', 'destinationHash', 'destinationName', 'destinationDescription', 'icon');
        self::loadDefinitions($data, 'places', 'placeHash', 'placeName', 'placeDescription');
        self::loadDefinitions($data, 'activityTypes', 'activityTypeHash', 'activityTypeName', 'identifier', 'icon');
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    /**
     * @param array $data Array of Definitions
     * @param string $index Index for this iteration
     * @param string $hash Index for hash of item
     * @param string $title Index for title of item
     * @param string $desc Index for description of item
     * @param null $extra Index for anything extra (optional)
     * @param null $secondary Index for anything secondary extra (optional)
     * @param null $third Index for a third extra field (optional)
     * @return bool
     */
    private static function loadDefinitions(&$data, $index, $hash, $title,
                                            $desc, $extra = null, $secondary = null, $third = null)
    {
        if (isset($data[$index]))
        {
            foreach($data[$index] as $item)
            {
                if (($mHash = Hash::where('hash', $item[$hash])->first()) == null)
                {
                    $mHash = new Hash();
                }

                // There are some records in the Hash response that have "FIELD_HIDDEN"
                // Probably from a future DLC, but we can't decode these. So skip em.
                if (! isset($item[$title])) continue;

                $mHash->hash = $item[$hash];
                $mHash->title = $item[$title];
                $mHash->description = isset($item[$desc]) ? $item[$desc] : null;
                $mHash->extra = ($extra != null) ? $item[$extra] : null;

                if ($secondary != null)
                {
                    $mHash->extraSecondary = isset($item[$secondary]) ? $item[$secondary] : null;
                }

                if ($third != null)
                {
                    $mHash->extraThird = isset($item[$third]) ? $item[$third] : null;
                }

                $mHash->save();
            }
        }

        return false;
    }
}
