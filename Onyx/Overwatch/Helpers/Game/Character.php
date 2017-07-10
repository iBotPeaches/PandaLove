<?php

namespace Onyx\Overwatch\Helpers\Game;

/**
 * Class Character.
 */
class Character
{
    /**
     * @return array
     */
    public static function getCharacters() : array
    {
        $characters = [
            'ana' => 'Ana',
            'bastion' => 'Bastion',
            'dva' => 'D.Va',
            'genji' => 'Genji',
            'hanzo' => 'Hanzo',
            'junkrat' => 'Junkrat',
            'lucio' => 'Lúcio',
            'mccree' => 'McCree',
            'mei' => 'Mei',
            'mercy' => 'Mercy',
            'pharah' => 'Pharah',
            'reaper' => 'Reaper',
            'reinhardt' => 'Reinhardt',
            'roadhog' => 'Roadhog',
            'soldier76' => 'Soldier: 76',
            'symmetra' => 'Symmetra',
            'torbjorn' => 'Torbjörn',
            'tracer' => 'Tracer',
            'widowmaker' => 'Widowmaker',
            'winston' => 'Winston',
            'zarya' => 'Zarya',
            'zenyatta' => 'Zenyatta',
            'sombra' => 'Sombra',
            'orisa' => 'Orisa',
            'doomfist' => 'Doomfist'
        ];

        asort($characters);
        return $characters;
    }

    /**
     * @param string $character
     * @return string
     */
    public static function image(string $character) : string
    {
        $character = strtolower($character);
        return asset('/images/overwatch/'.$character.'.png');
    }

    /**
     * @param string $char
     * @return string
     */
    public static function getValidCharacter(string $char) : string
    {
        switch ($char) {
            case 'reinhardt':
            case 'rein':
                return 'reinhardt';

            case 'mccree':
                return 'mccree';

            case 'winston':
                return 'winston';

            case 'pharah':
                return 'pharah';

            case 'roadhog':
            case 'road':
                return 'roadhog';

            case 'zarya':
                return 'zarya';

            case 'torbjorn':
            case 'torb':
                return 'torbjorn';

            case 'ana':
                return 'ana';

            case 'genji':
                return 'genji';

            case 'symmetra':
            case 'sim':
            case 'sym':
                return 'symmetra';

            case 'dva':
                return 'dva';

            case 'lucio':
                return 'lucio';

            case 'zen':
            case 'zenyatta':
                return 'zenyatta';

            case 'junkrat':
            case 'junk':
                return 'junkrat';

            case 'hanzo':
                return 'hanzo';

            case 'mercy':
                return 'mercy';

            case 'reaper':
                return 'reaper';

            case 'soldier76':
            case '76':
                return 'soldier76';

            case 'osira':
                return 'osira';

            case 'sombra':
                return 'sombra';

            case 'doomfist':
            case 'doom':
                return 'doomfist';

            case 'bastion':
                return 'bastion';

            case 'mei':
                return 'mei';

            default:
                return 'unknown';
        }
    }

    /**
     * @param array $data
     * @param string $category
     * @param string $stat
     * @return array
     * @throws \Exception
     */
    public static function orderBasedOnStats(array $data, string $category, string $stat) : array
    {
        $heros = collect($data);

        // Check if stat exists.
        $hero = $heros->first();
        if (array_get($hero['data'], $category.'.'.$stat) === null) {
            throw new \Exception('This stat does not exist.');
        }

        // Order based on that stat
        return $heros->sortByDesc(function($hero) use ($category, $stat) {
            return array_get($hero['data'], $category.'.'.$stat, 0);
        })->toArray();
    }
}
