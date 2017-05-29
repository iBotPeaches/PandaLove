<?php

namespace Onyx\Halo5\Enums;

abstract class GameMode
{
    /**
     * Error.
     */
    const Error = 0;

    /**
     * Arena.
     */
    const Arena = 1;

    /**
     * Campaign.
     */
    const Campaign = 2;

    /**
     * Custom.
     */
    const Custom = 3;

    /**
     * Wrzone.
     */
    const Warzone = 4;

    /**
     * @param $id
     * @param bool $slug
     *
     * @return string
     */
    public static function getName($id, $slug = false)
    {
        $name = '';

        switch ($id) {
            case self::Error:
                $name = 'Error';
                break;

            case self::Arena:
                $name = 'Arena';
                break;

            case self::Campaign:
                $name = 'Campaign';
                break;

            case self::Custom:
                $name = 'Custom';
                break;

            case self::Warzone:
                $name = 'Warzone';
                break;
        }

        if ($slug) {
            return strtolower($name);
        }

        return $name;
    }
}
