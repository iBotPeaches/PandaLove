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
     * @throws \Onyx\Destiny\Helpers\String\HashNotLocatedException
     */
    private function setAttributePullImage($index, $hash)
    {
        Images::saveImageLocally($this->translator->map($hash, false));
        $this->attributes[$index] = $hash;
    }
}
