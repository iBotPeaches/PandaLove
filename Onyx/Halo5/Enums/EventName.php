<?php

namespace Onyx\Halo5\Enums;

abstract class EventName
{
    /**
     * Unknown.
     *
     * Not possible.
     */
    const Unknown = 0;

    /**
     * Death.
     */
    const Death = 1;

    /**
     * RoundStart.
     */
    const RoundStart = 2;

    /**
     * WeaponPickup.
     */
    const WeaponPickup = 3;

    /**
     * PlayerSpawn.
     */
    const PlayerSpawn = 4;

    /**
     * Medal.
     */
    const Medal = 5;

    /**
     * WeaponDrop.
     */
    const WeaponDrop = 6;

    /**
     * WeaponPickupPad.
     */
    const WeaponPickupPad = 7;

    /**
     * RoundEnd.
     */
    const RoundEnd = 8;

    /**
     * Impulse.
     */
    const Impulse = 9;

    /**
     * @param $name
     *
     * @throws \Exception
     *
     * @return int
     */
    public static function getId($name)
    {
        switch ($name) {
            case 'Death':
                return self::Death;

            case 'RoundStart':
                return self::RoundStart;

            case 'RoundEnd':
                return self::RoundEnd;

            case 'WeaponPickup':
                return self::WeaponPickup;

            case 'WeaponDrop':
                return self::WeaponDrop;

            case 'WeaponPickupPad':
                return self::WeaponPickupPad;

            case 'PlayerSpawn':
                return self::PlayerSpawn;

            case 'Medal':
                return self::Medal;

            case 'Impulse':
                return self::Impulse;

            default:
                throw new \Exception($name.' Could not be found.');
        }
    }

    /**
     * @param $id
     *
     * @return string
     */
    public static function getSeo($id)
    {
        switch ($id) {
            case self::RoundStart:
                return 'round-start';

            case self::RoundEnd:
                return 'round-end';

            case self::Death:
                return 'death';

            case self::Impulse:
                return 'impulse';

            case self::Medal:
                return 'medal';

            case self::PlayerSpawn:
                return 'player-spawn';

            case self::WeaponDrop:
                return 'weapon-drop';

            case self::WeaponPickup:
                return 'weapon-pickup';

            case self::WeaponPickupPad:
                return 'weapon-pickup-pad';

            default:
                return 'this-doesnt-exist-so-will-fail';
        }
    }
}
