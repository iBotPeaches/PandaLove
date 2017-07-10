<?php

namespace PandaLove\Http\Controllers\Overwatch;

use Illuminate\Http\Request;
use Onyx\Overwatch\Client;
use Onyx\Overwatch\Helpers\Game\Character;
use PandaLove\Http\Controllers\Controller;

class StatsController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function getIndex()
    {
        return view('overwatch.stats', [
            'heros' => Character::getCharacters()
        ]);
    }

    public function getCharacter(string $character = '', string $stat = 'time_spent_on_fire_average')
    {
        $client = new Client();
        $character = Character::getValidCharacter($character);

        if ($character === 'unknown') {
            throw new \Exception('This character could not be located');
        }

        // load viable options
        $hero = $client->getMostPlaytimeChar($character);

        if ($hero === null) {
            throw new \Exception('This character could not be loaded.');
        }

        return view('overwatch.character', [
            'hero' => $hero
        ]);
        $options = '';
        $test = '';
    }
}
