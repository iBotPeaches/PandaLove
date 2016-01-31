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

    /**
     * @param $value
     * @return string
     */
    public static function getProperFormat($value)
    {
        switch (strtolower($value))
        {
            case "raid":
                return 'Raid';

            case "flawless":
                return 'Flawless';

            case "pvp":
                return 'PVP';

            case "poe":
                return 'PoE';

            case "too":
                return 'ToO';

            case "campaign":
                return 'Campaign';

            case "arena":
                return 'Arena';

            case "slayer":
                return 'Slayer';

            case "btb":
                return 'Big Team Battle';

            case "wza":
            case "warzone":
                return 'Warzone';

            case "doubles":
                return 'Doubles';

            case "custom":
                return 'Custom Game';

            case "Forge":
                return 'Forge';
        }
    }
}