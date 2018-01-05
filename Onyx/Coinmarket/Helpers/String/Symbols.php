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
            default:
                return $name;
        }
    }
}