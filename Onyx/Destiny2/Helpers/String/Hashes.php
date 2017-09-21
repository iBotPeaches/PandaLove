<?php

namespace Onyx\Destiny2\Helpers\String;

use Onyx\Destiny2\Client;

/**
 * Class Hashes
 * @package Onyx\Destiny2\Helpers\String
 */
class Hashes
{
    /**
     * @var Client
     */
    private static $client;

    /**
     * Hashes constructor.
     */
    public function __construct()
    {
        self::$client = new Client();
    }

    /**
     * @param $type
     * @param $hash
     * @return mixed
     */
    public static function getHash($type, $hash)
    {
        if (self::$client === null) {
            self::$client = new Client();
        }
        return self::$client->getHash($type, $hash);
    }
}