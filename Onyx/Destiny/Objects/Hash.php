<?php namespace Onyx\Destiny\Objects;

use Illuminate\Database\Eloquent\Model;

class Hash extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hashes';

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
        self::loadDefinitions($data, 'activities', 'activityHash', 'activityName', 'activityDescription', 'activityLevel', 'pgcrImage');
        self::loadDefinitions($data, 'classes', 'classHash', 'className', '');
        self::loadDefinitions($data, 'genders', 'genderHash', 'genderName', 'genderType');
        self::loadDefinitions($data, 'races', 'raceHash', 'raceName', 'raceDescription');
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
     * @return bool
     */
    private static function loadDefinitions(&$data, $index, $hash, $title, $desc, $extra = null, $secondary = null)
    {
        if (isset($data[$index]))
        {
            foreach($data[$index] as $item)
            {
                if ($mHash = Hash::where('hash', $item[$hash])->first() != null) continue;

                $mHash = new Hash();
                $mHash->hash = $item[$hash];
                $mHash->title = $item[$title];
                $mHash->description = isset($item[$desc]) ? $item[$desc] : null;
                $mHash->extra = ($extra != null) ? $item[$extra] : null;

                if ($secondary != null)
                {
                    $mHash->extraSecondary = isset($item[$secondary]) ? $item[$secondary] : null;
                }

                $mHash->save();
            }
        }

        return false;
    }
}
