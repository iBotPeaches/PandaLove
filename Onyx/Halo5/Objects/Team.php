<?php namespace Onyx\Halo5\Objects;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * @package Onyx\Halo5\Objects
 * @property integer $id
 * @property string $name
 * @property string $color
 * @property string $contentId
 */
class Team extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'halo5_teams';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Disable timestamps
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

    /**
     * @return mixed
     */
    public function getImage()
    {
        $path = public_path('images/teams/');

        if (file_exists($path . $this->id . '.png'))
        {
            return asset('images/teams/' . $this->id . '.png');
        }
        else
        {
            return asset('images/unknown-weapon.png');
        }
    }

    /**
     * @return string
     */
    public function getSemanticColor()
    {
        switch ($this->id)
        {
            case 0:
                return 'red';
            
            case 1:
                return 'blue';
            
            case 2:
                return 'yellow';
            
            case 3:
                return 'green';
            
            case 4:
                return 'violet';
            
            case 5:
                return 'pink';
            
            case 6:
                return 'orange';
            
            case 7:
                return 'teal';
        }
    }
}
