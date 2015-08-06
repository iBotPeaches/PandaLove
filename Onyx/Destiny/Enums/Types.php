<?php namespace Onyx\Destiny\Enums;

abstract class Types {

    /**
     * A regular ole raid
     */
    const Raid = 'Raid';

    /**
     * A raid with no deaths for anyone, thus flawless.
     */
    const Flawless = 'Flawless Raid';

    /**
     * A regular ole PVP match (not to be confused with Trials of Osiris)
     */
    const PVP = 'PVP';

    /**
     * A regular ole PoE (lvl 28 -> 35)
     */
    const PoE = 'Prison Of Elders';

    /**
     * The competitive Crucible PVP mode
     */
    const ToO = 'Trials of Osiris';

    /**
     * @return array
     */
    public static function getAll()
    {
        return [
            'Raid' => self::Raid,
            'Flawless' => self::Flawless,
            'PVP' => self::PVP,
            'PoE' => self::PoE,
            'ToO' => self::ToO
        ];
    }
}