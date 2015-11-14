<?php namespace Onyx\Halo5\Helpers\String;

use Illuminate\Support\Str;

class Text {

    /**
     * Returns seo friendly Gamertag
     *
     * EX: iBotPeaches v5 -> ibotpeaches+v5
     *
     * @param $gamertag
     * @return string
     */
    public static function encodeGamertagForApi($gamertag)
    {
        return Str::lower(str_replace(" ", "+", $gamertag));
    }
}