<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

    public static function boot()
    {
        parent::boot();

        Game::deleting(function($game)
        {
            foreach($game->players as $player)
            {
                $player->delete();
            }

            $game->comments()->delete();

            if ($game->pvp instanceof PVP)
            {
                $game->pvp->delete();
            }
        });
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
        $this->attributes['occurredAt'] = new Carbon($value, 'America/Chicago');
    }

    public function getIsHardAttribute($value)
    {
        return boolval($value);
    }

    public function getOccurredAtAttribute($value)
    {
        $date = new Carbon($value);
        $date = $date->timezone('America/Chicago');

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

    public function pvp()
    {
        return $this->hasOne('Onyx\Destiny\Objects\PVP', 'instanceId', 'instanceId');
    }

    public function teamPlayers($team_id)
    {
        $players = $this->players->filter(function($player) use ($team_id)
        {
            return $player->team == $team_id;
        });

        return $players;
    }

    public function comments()
    {
        return $this->morphMany('Onyx\Destiny\Objects\Comment', 'commentable')
            ->where('parent_comment_id', 0)
            ->orderBy('created_at', 'DESC');
    }

    public function findAccountViaMembershipId($membershipId, $returnAccount = true)
    {
        foreach($this->players as $player)
        {
            if ($player->membershipId == $membershipId)
            {
                if ($returnAccount == false)
                {
                    return $player;
                }
                else
                {
                    return $player->account;
                }
            }
        }

        return \Onyx\Account::where('membershipId', $membershipId)->first();
    }

    public function completed()
    {
        $count = 0;
        foreach($this->getRelation('players') as $player)
        {
            if ($player->completed && $player->historyAccount != null)
            {
                if ($player->historyAccount->clanName == "Panda Love")
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

    public function scopeSingular($query)
    {
        return $query->where('raidTuesday', 0)->orderBy('occurredAt', 'DESC');
    }

    public function scopeOfTuesday($query, $value)
    {
        return $query->where('raidTuesday', $value)->orderBy('occurredAt', 'DESC');
    }

    public function scopeOfPassage($query, $value)
    {
        return $query->where('passageId', $value)->orderBy('occurredAt', 'ASC');
    }

    public function scopeRaid($query)
    {
        return $query->where('type', 'Raid');
    }

    public function scopeToO($query)
    {
        return $query->where('type', 'ToO');
    }

    public function scopeFlawless($query)
    {
        return $query->where('type', 'Flawless');
    }

    public function scopeTuesday($query)
    {
        return $this->scopeRaid($query)
            ->selectRaw('*, count(*) as raidCount, sum(timeTookInSeconds) as totalTime')
            ->groupBy('raidTuesday')
            ->orderBy('occurredAt', 'DESC')
            ->having('raidTuesday', '>', 0);
    }

    public function scopeMultiplayer($query)
    {
        return $query->where('type', 'PVP');
    }

    public function scopePoE($query)
    {
        return $query->where('type', 'PoE');
    }

    public function scopePassage($query)
    {
        return $this->scopeToO($query)
            ->selectRaw('*, count(*) as gameCount, sum(timeTookInSeconds) as totalTime')
            ->groupBy('passageId')
            ->orderBy('occurredAt', 'ASC')
            ->having('passageId', '>', 0);
    }

    public function type()
    {
        return $this->translator->map($this->referenceId, false);
    }

    public function getRawSeconds()
    {
        return $this->attributes['timeTookInSeconds'];
    }

    public function isPoE()
    {
        return $this->type == "PoE";
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
