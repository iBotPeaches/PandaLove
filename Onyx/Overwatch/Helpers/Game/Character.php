<?php

namespace Onyx\Overwatch\Helpers\Game;
/**
 * Class Character
 * @package Onyx\Overwatch\Helpers\Game
 */
class Character
{
    public static function getValidCharacter(string $char) : string
    {
        switch ($char)
        {
            case 'reinhardt':
            case 'rein':
                return 'reinhardt';

            case 'mcree':
                return 'mcree';

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

            default:
                return 'unknown';
        }
    }
}