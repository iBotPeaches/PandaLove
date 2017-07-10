<?php

namespace Onyx\Overwatch\Helpers\String;

/**
 * Class Text
 * @package Onyx\Overwatch\Helpers\String
 */
class Text
{
    /**
     * @param string $word
     * @return string
     */
    public static function label(string $word)
    {
        return str_replace('_', ' ', title_case($word));
    }
}
