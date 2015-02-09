<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Helpers\String\Hashes;

class Game extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var \Onyx\Destiny\Helpers\String\Hashes $translator
     */
    private $translator;

    function __construct()
    {
        parent::__construct();

        $this->translator = new Hashes();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    public function setOccurredAtAttribute($value)
    {
        $this->attributes['occurred_at'] = new Carbon($value);
    }

    public function set1_EmblemAttribute($value)
    {
        $this->attributes['1_emblem'] = $value;
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function setTranslatorUrl($url)
    {
        $this->translator->setUrl($url);
    }
}
