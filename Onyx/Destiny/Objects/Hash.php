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
    protected $fillable = ['hash', 'title', 'description', 'extra'];

    /**
     * @var bool
     */
    public $timestamps = false;

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public static function loadHashesFromApi($data)
    {
        self::loadDefinitionRaces(array_get($data, 'races', []));
        self::loadDefinitionGenders(array_get($data, 'genders', []));
        self::loadDefinitionClasses(array_get($data, 'classes', []));
        self::loadDefinitionActivities(array_get($data, 'activities', []));
        self::loadDefinitionItems(array_get($data, 'items', []));
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    private static function loadDefinitionItems($items)
    {
        foreach($items as $item)
        {
            if ($hash = Hash::where('hash', $item['itemHash'])->first() != null) continue;

            $hash = new Hash();
            $hash->hash = $item['itemHash'];
            $hash->title = $item['itemName'];
            $hash->description = isset($item['itemDescription']) ? $item['itemDescription'] : '';
            $hash->extra = $item['icon'];
            $hash->save();
        }
    }

    private static function loadDefinitionActivities($activities)
    {
        foreach($activities as $activity)
        {
            if ($hash = Hash::where('hash', $activity['activityHash'])->first() != null) continue;

            $hash = new Hash();
            $hash->hash = $activity['activityHash'];
            $hash->title = $activity['activityName'];
            $hash->description = $activity['activityDescription'];
            $hash->save();
        }
    }

    private static function loadDefinitionClasses($classes)
    {
        foreach($classes as $class)
        {
            if ($hash = Hash::where('hash', $class['classHash'])->first() != null) continue;

            $hash = new Hash();
            $hash->hash = $class['classHash'];
            $hash->title = $class['className'];
            $hash->save();
        }
    }

    private static function loadDefinitionGenders($genders)
    {
        foreach($genders as $gender)
        {
            if ($hash = Hash::where('hash', $gender['genderHash'])->first() != null) continue;

            $hash = new Hash();
            $hash->hash = $gender['genderHash'];
            $hash->title = $gender['genderName'];
            $hash->extra = $gender['genderType'];
            $hash->save();
        }
    }

    private static function loadDefinitionRaces($races)
    {
        foreach($races as $race)
        {
            if ($hash = Hash::where('hash', $race['raceHash'])->first() != null) continue;

            $hash = new Hash();
            $hash->hash = $race['raceHash'];
            $hash->title = $race['raceName'];
            $hash->description = $race['raceDescription'];
            $hash->save();
        }
    }

}
