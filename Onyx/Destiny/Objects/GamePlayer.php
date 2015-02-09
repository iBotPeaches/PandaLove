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
        Images::saveImagesLocally($this->translator->map($hash, false));
        $this->attributes[$index] = $hash;
    }
}
