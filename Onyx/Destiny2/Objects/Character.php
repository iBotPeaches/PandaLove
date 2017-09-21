<?php

namespace Onyx\Destiny2\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny2\Helpers\String\Hashes;

/**
 * Class Character.
 *
 * @property int $id
 * @property string $characterId
 * @property Carbon $lastPlayed
 * @property int $minutesPlayedTotal
 * @property int $light
 * @property int $max_light
 * @property string $raceHash
 * @property string $genderHash
 * @property string $classHash
 * @property string $emblemPath
 * @property string $backgroundPath
 * @property string $emblemHash
 * @property int $level
 */
class Character extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'destiny2_characters';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setLightAttribute($attribute)
    {
        $max = $this->attributes['max_light'] ?? 0;
        if ($attribute > $max) {
            $this->attributes['max_light'] = $attribute;
        }
        $this->attributes['light'] = $attribute;
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function name()
    {
        return $this->destinyClass();
    }

    public function nameWithLight()
    {
        return $this->name().' - '.$this->max_light;
    }

    public function emblem()
    {
        return url('https://www.bungie.net/'.$this->emblemPath);
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    private function destinyClass()
    {
        $hash = Hashes::getHash('DestinyClassDefinition', $this->classHash);

        return array_get($hash, 'displayProperties.name');
    }
}
