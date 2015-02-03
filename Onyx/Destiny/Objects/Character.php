<?php namespace Onyx\Destiny\Objects;

use Illuminate\Database\Eloquent\Model;
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

    public function setSubclassAttribute($value)
    {
        $this->setAttributePullImage('subclass', $value, 'other');
    }

    public function setHelmetAttribute($value)
    {
        $this->setAttributePullImage('helmet', $value, 'armor');
    }

    public function setArmsAttribute($value)
    {
        $this->setAttributePullImage('arms', $value, 'armor');
    }

    public function setChestAttribute($value)
    {
        $this->setAttributePullImage('chest', $value, 'armor');
    }

    public function setBootsAttribute($value)
    {
        $this->setAttributePullImage('boots', $value, 'armor');
    }

    public function setClassItemAttribute($value)
    {
        $this->setAttributePullImage('class_item', $value, 'armor');
    }

    public function setPrimaryAttribute($value)
    {
        $this->setAttributePullImage('primary', $value, 'weapons');
    }

    public function setSecondaryAttribute($value)
    {
        $this->setAttributePullImage('secondary', $value, 'weapons');
    }

    public function setHeavyAttribute($value)
    {
        $this->setAttributePullImage('heavy', $value, 'weapons');
    }

    public function setShipAttribute($value)
    {
        $this->setAttributePullImage('ship', $value, 'other');
    }

    public function setSparrowAttribute($value)
    {
        $this->setAttributePullImage('sparrow', $value, 'other');
    }

    public function setGhostAttribute($value)
    {
        $this->setAttributePullImage('ghost', $value, 'other');
    }

    public function setShaderAttribute($value)
    {
        $this->setAttributePullImage('shader', $value, 'other');
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function account()
    {
        return $this->belongsTo('Onyx\Account');
    }

    public function setTranslatorUrl($url)
    {
        $this->translator->setUrl($url);
    }

    //---------------------------------------------------------------------------------
    // Private Methods
    //---------------------------------------------------------------------------------

    /**
     * @param string $index Index for $this->attributes
     * @param string $hash hashCode for item
     * @param string $type ('other', 'armor', 'weapons')
     * @throws \Onyx\Destiny\Helpers\String\HashNotLocatedException
     */
    private function setAttributePullImage($index, $hash, $type = 'other')
    {
        Images::saveImageLocally($this->translator->map($hash, false), $type);
        $this->attributes[$index] = $hash;
    }
}
