<?php namespace Onyx\Halo5\Helpers\String;

use Illuminate\Support\Str;
use Onyx\Account;
use Onyx\Halo5\Objects\Rank;

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
        return Str::lower(str_replace(" ", "+", trim($gamertag)));
    }

    /**
     * @param $account Account
     * @return array
     */
    public static function buildProgressBar($account)
    {
        $spartanRank = $account->h5->spartanRank;

        $nextLevel = Rank::where('previousLevel', $spartanRank)->first();
        $level = Rank::where('level', $spartanRank)->first();

        $delta = $nextLevel->startXp - $level->startXp;
        
        return [
            'max' => $delta,
            'current' => $account->h5->Xp - $level->startXp,
            'next' => $nextLevel
        ];
    }
}