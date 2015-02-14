<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Onyx\Destiny\Enums\LightLevels;
use Onyx\Destiny\Helpers\Assets\Images;
use Onyx\Destiny\Helpers\String\Hashes;

class Character extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'characters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    private $translator;

    function __construct()
    {
        parent::__construct();

        $this->translator = new Hashes();
    }

    public static function boot()
    {
        parent::boot();

        Character::saving(function($character)
        {

        });
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setEmblemAttribute($value)
    {
        $this->setAttributePullImage('emblem', $value);
    }

    public function setBackgroundAttribute($value)
    {
        $this->setAttributePullImage('background', $value);
    }

    public function setSubclassAttribute($value)
    {
        $this->setAttributePullImage('subclass', $value);
    }

    public function setHelmetAttribute($value)
    {
        $this->setAttributePullImage('helmet', $value);
    }

    public function setArmsAttribute($value)
    {
        $this->setAttributePullImage('arms', $value);
    }

    public function setChestAttribute($value)
    {
        $this->setAttributePullImage('chest', $value);
    }

    public function setBootsAttribute($value)
    {
        $this->setAttributePullImage('boots', $value);
    }

    public function setClassItemAttribute($value)
    {
        $this->setAttributePullImage('class_item', $value);
    }

    public function setPrimaryAttribute($value)
    {
        $this->setAttributePullImage('primary', $value);
    }

    public function setSecondaryAttribute($value)
    {
        $this->setAttributePullImage('secondary', $value);
    }

    public function setHeavyAttribute($value)
    {
        $this->setAttributePullImage('heavy', $value);
    }

    public function setShipAttribute($value)
    {
        $this->setAttributePullImage('ship', $value);
    }

    public function setSparrowAttribute($value)
    {
        $this->setAttributePullImage('sparrow', $value);
    }

    public function setGhostAttribute($value)
    {
        $this->setAttributePullImage('ghost', $value);
    }

    public function setShaderAttribute($value)
    {
        $this->setAttributePullImage('shader', $value);
    }

    public function getMinutesPlayedAttribute($value)
    {
        $time = Carbon::now()->addMinutes($value);

        $days = $time->diffInDays();
        $hours = $time->subDays($days)->diffInHours();
        $minutes = $time->subHours($hours)->diffInMinutes();

        $rtr = '';

        if ($days > 0)
        {
            $name = ($days > 1) ? 'days' : 'day';
            $rtr .= $days . " " . $name . " ";
        }

        if ($hours > 0)
        {
            $name = ($hours > 1) ? 'hours' : 'hour';
            $rtr .= $hours . " " . $name . " ";
        }

        if ($days == 0)
        {
            $name = ($minutes > 1) ? 'minutes' : 'minute';
            $rtr .= $minutes . " " . $name . ".";
        }

        return $rtr;
    }

    public function getRaceAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getGenderAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getClassAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getEmblemAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getBackgroundAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getSubclassAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getHelmetAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getArmsAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getChestAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getBootsAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getPrimaryAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getSecondaryAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getHeavyAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getClassItemAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getShipAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getSparrowAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getGhostAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    public function getShaderAttribute($value)
    {
        return $this->translator->map($value, false);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }

    public function players()
    {
        return $this->belongsToMany('Onyx\Destiny\Objects\Game');
    }

    public function setTranslatorUrl($url)
    {
        $this->translator->setUrl($url);
    }

    public function name()
    {
        return $this->level . " " . $this->class->title;
    }

    public function armor()
    {
        return [
            $this->helmet,
            $this->arms,
            $this->chest,
            $this->boots
        ];
    }

    public function weapons()
    {
        return [
            $this->primary,
            $this->secondary,
            $this->heavy,
            $this->class_item
        ];
    }

    public function other()
    {
        $attrs = ['ship', 'sparrow', 'ghost', 'shader'];

        $data = [];
        foreach ($attrs as $attribute)
        {
            if (strlen($this->attributes[$attribute]) > 0)
            {
                $data[] = $this->{$attribute};
            }
        }

        return $data;
    }

    public function getLastUpdatedRelative()
    {
        $date = new Carbon($this->updated_at);

        return $date->diffForHumans();
    }

    public function getMinutesPlayedRaw()
    {
        return $this->attributes['minutes_played'];
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    /**
     * @param string $index Index for $this->attributes
     * @param string $hash hashCode for item
     * @throws \Onyx\Destiny\Helpers\String\HashNotLocatedException
     */
    private function setAttributePullImage($index, $hash)
    {
        if ($hash == null || $hash == "") return;
        Images::saveImagesLocally($this->translator->map($hash, false));
        $this->attributes[$index] = $hash;
    }
}
