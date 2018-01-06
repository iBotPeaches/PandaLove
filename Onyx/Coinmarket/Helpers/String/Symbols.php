<?php
declare(strict_types=1);

namespace Onyx\Coinmarket\Helpers\String;

/**
 * Class Symbols
 * @package Onyx\Coinmarket\Helpers\String
 */
class Symbols
{
    /**
     * @param string $name
     * @return string
     */
    public static function getTickerId(string $name): string
    {
        $name = strtolower($name);

        switch ($name) {
            case 'btc':
                return 'bitcoin';
            case 'ltc':
                return 'litecoin';
            case 'trx':
                return 'tron';
            case 'xrp':
                return 'ripple';
            case 'ftc':
                return 'feathercoin';
            case 'powr':
                return 'power-ledger';
            case 'wtc':
                return 'walton';
            case 'xlm':
                return 'stellar';
            case 'xvg':
                return 'verge';
            default:
                return $name;
        }
    }
}