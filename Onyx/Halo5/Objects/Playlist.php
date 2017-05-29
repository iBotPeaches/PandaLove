<?php

namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Playlist.
 *
 * @property int $id
 * @property string $contentId
 * @property string $name
 * @property string $description
 * @property bool $isRanked
 * @property string $imageUrl
 * @property bool isActive
 * @property string $gameMode
 */
class Playlist extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_playlists';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $primaryKey = 'contentId';

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
    }

    //---------------------------------------------------------------------------------
    // Accessors & Mutators
    //---------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------
    // Public Methods
    //---------------------------------------------------------------------------------

    public function season()
    {
        return $this->hasOne('Onyx\Halo5\Objects\Season', 'contentId', 'seasonId');
    }

    public static function getAll()
    {
        return \Cache::remember('playlists-metadata', 120, function () {
            $items = [];

            foreach (Playlist::all() as $playlist) {
                $items[$playlist->contentId] = $playlist;
            }

            return $items;
        });
    }
}
