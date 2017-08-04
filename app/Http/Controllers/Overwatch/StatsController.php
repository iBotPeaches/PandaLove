<?php

namespace PandaLove\Http\Controllers\Overwatch;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Onyx\Overwatch\Helpers\Game\Character;
use Onyx\Overwatch\Objects\Character as CharacterModel;
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
            'heros' => Character::getCharacters(),
        ]);
    }

    public function getCharacter(string $character = '', string $category = 'general_stats', string $stat = 'all_damage_done')
    {
        $character = Character::getValidCharacter($character);

        if ($character === 'unknown') {
            throw new \Exception('This character could not be located');
        }

        $heros = CharacterModel::with(['stats.account.user'])
            ->where('character', $character)
            ->where('playtime', '>', 0)
            ->whereHas('stats.account.user', function (Builder $query) {
                $query->where('isPanda', true);
            })
            ->orderBy('playtime', 'desc')
            ->limit(20)
            ->get()
            ->toArray();

        if (empty($heros)) {
            return view('alert', [
                'message' => [
                    'body'   => 'Character has no stats.',
                    'header' => 'Uh oh',
                    'type'   => 'red',
                ],
            ]);
        }

        return view('overwatch.character', [
            'hero'     => $heros[0],
            'stat'     => $stat,
            'category' => $category,
            'heros'    => Character::orderBasedOnStats($heros, $category, $stat),
        ]);
    }
}
