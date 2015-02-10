<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Onyx\Destiny\Helpers\Assets\Images;
use Onyx\Destiny\Helpers\String\Hashes;
use Onyx\Destiny\Helpers\String\Text;

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

    public function setReferenceIdAttribute($value)
    {
        $this->setAttributePullImage('referenceId', $value);
        $object = $this->translator->map($value, false);

        $hard = false;
        if (str_contains($object->title, 'Crota'))
        {
            if ($object->extraThird == 33)
            {
                $hard = true;
            }
        }
        else if (str_contains($object->title, 'Vault'))
        {

            if ($object->extraThird == 30)
            {
                $hard = true;
            }
        }

        $this->attributes['isHard'] = boolval($hard);
    }

    public function setOccurredAtAttribute($value)
    {
        $this->attributes['occurredAt'] = new Carbon($value);
    }

    public function getIsHardAttribute($value)
    {
        return boolval($value);
    }

    public function getOccurredAtAttribute($value)
    {
        $date = new Carbon($value);

        if ($date->diffInDays() > 30)
        {
            return $date->format('M j, Y - g:ma');
        }
        else
        {
            return $date->diffForHumans();
        }
    }

    public function getTimeTookInSecondsAttribute($value)
    {
        return Text::timeDuration($value);
    }

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function players()
    {
        return $this->hasMany('Onyx\Destiny\Objects\GamePlayer', 'game_id', 'instanceId');
    }

    public function completed()
    {
        $count = 0;
        foreach($this->getRelation('players') as $player)
        {
            if ($player->account != null)
            {
                if ($player->account->clanName == "Panda Love")
                {
                    $count++;
                }
            }
        }

        return $count;
    }

    public function setTranslatorUrl($url)
    {
        $this->translator->setUrl($url);
    }

    public function type()
    {
        return $this->translator->map($this->referenceId, false);
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
