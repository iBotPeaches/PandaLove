<?php namespace Onyx\Destiny\Objects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model {

    protected $table = 'game_events';

    protected $fillable = ['title'];

    public $timestamps = true;
}