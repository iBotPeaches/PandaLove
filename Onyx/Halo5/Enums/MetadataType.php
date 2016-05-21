<?php namespace Onyx\Halo5\Enums;

abstract class MetadataType {

    /**
     * Weapon - halo5_weapons
     */
    const Weapon = 5;

    /**
     * Vehicle - halo5_vehicles
     */
    const Vehicle = 10;

    /**
     * Enemy - halo5_enemies
     */
    const Enemy = 15;

    /**
     * Impulses - halo5_impulses
     */
    const Impulses = 20;

    /**
     * Medal - halo5_medals
     */
    const Medal = 25;

    /**
     * Impulses that spam the event feed every second are here.
     * This allows us to find the start/end of an impulse and
     * create a single event that is from start to end
     * vs spamming events every second.
     * @var array
     */
    public static $tickingImpulses = [
        '2483589021', // Ball Held Duration
        '588422610', // Time Survived Tick
    ];

    /**
     * @param $uuid
     * @return bool
     */
    public static function isTickingImpulse($uuid)
    {
        return in_array($uuid, self::$tickingImpulses);
    }
}