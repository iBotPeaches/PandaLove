<?php

namespace Onyx\Halo5\Enums;

abstract class DeathType
{
    /**
     * Assassination.
     *
     * Describes if the death was committed by the killer from behind (Assassination or melee to back).
     */
    const Assassination = 0;

    /**
     * GroundPound.
     *
     * Describes if the kill was committed by the killer with a ground pound.
     */
    const GroundPound = 1;

    /**
     * Headshot.
     *
     * Describes if the kill was committed by the killer with a head shot.
     */
    const Headshot = 2;

    /**
     * Melee.
     *
     * Describes if the kill was committed by the killer using melee.
     */
    const Melee = 3;

    /**
     * ShoulderBash.
     *
     * Describes if the kill was committed by the killer with a shoulder bash.
     */
    const ShoulderBash = 4;

    /**
     * Weapon.
     *
     * Describes if the kill was committed by the killer with a weapon.
     */
    const Weapon = 5;

    public static function getId($name)
    {
        switch ($name) {
            case 'IsAssassination':
                return self::Assassination;

            case 'IsGroundPound':
                return self::GroundPound;

            case 'IsHeadshot':
                return self::Headshot;

            case 'IsMelee':
                return self::Melee;

            case 'IsShoulderBash':
                return self::ShoulderBash;

            case 'IsWeapon':
                return self::Weapon;

            default:
                throw new \Exception($name.' is an unknown type');
        }
    }
}
