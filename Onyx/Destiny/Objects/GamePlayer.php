<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Constants;
use Onyx\Destiny\Helpers\Assets\Images;
use Onyx\Destiny\Helpers\String\Hashes;

class GamePlayer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'game_players';

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

    public function setEmblemAttribute($value)
    {
        $hash = Hash::where('extra', $value)->first();

        if (! $hash instanceof Hash)
        {
            $this->translator->setUrl(sprintf(Constants::$explorerItems, 160, 'Emblem'));
            $this->translator->updateHashes(true);

            $this->setEmblemAttribute($value);
        }

        $this->setAttributePullImage('emblem', $hash->hash);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function game()
    {
        return $this->belongsTo('Onyx\Destiny\Objects\Game');
    }

    public function character()
    {
        return $this->hasOne('Onyx\Destiny\Objects\Character', 'characterId', 'characterId');
    }

    public function account()
    {
        return $this->hasOne('Onyx\Account', 'membershipId', 'membershipId');
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
        if ($hash == null || $hash == "") return;
        Images::saveImagesLocally($this->translator->map($hash, false));
        $this->attributes[$index] = $hash;
    }
}
