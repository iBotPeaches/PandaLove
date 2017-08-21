<?php

namespace Onyx\Destiny\Helpers\Assets;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;
use Onyx\Destiny\Objects\Hash;

class Images
{
    /**
     * @param \Onyx\Destiny\Objects\Hash $hash
     *
     * @return bool
     */
    public static function saveImagesLocally($hash)
    {
        self::saveImageLocally($hash, 'extra');

        // check for extraSecondary
        if ($hash->extraSecondary != null && !str_contains($hash->extraSecondary, 'missing_icon')) {
            self::saveImageLocally($hash, 'extraSecondary');
        }
    }

    /**
     * @param \Onyx\Destiny\Objects\Hash $hash
     * @param string                     $index
     *
     * @return bool
     */
    public static function saveImageLocally($hash, $index = 'extra')
    {
        // BUG: Can't use variable object indexes implicitly
        // $hash->{$index} should work but doesn't
        // map the index explicitly with the attributes dumped into $bug
        $bug = $hash->getAttributes();
        $url = 'https://bungie.net'.$bug[$index];
        $name = (($index != 'extra') ? '_bg' : null);
        $name = $hash->hash.$name;

        // Make sure we aren't trying to save something that isn't an image
        // We only need this check because we cheat and store all hash related objects
        // in one table. This means we have crazy cheats to get things done.
        if (strlen($bug[$index]) < 5) {
            return false;
        }

        $location = public_path('uploads/thumbs/');
        $filename = $name.'.'.pathinfo($bug[$index], PATHINFO_EXTENSION);

        if (File::isFile($location.$filename)) {
            return true;
        }

        if ($hash instanceof Hash) {
            $manager = new ImageManager();

            try {
                $img = $manager->make($url);
                $img->save($location.$filename);
            } catch (NotReadableException $e) {
                Log::error('Could not download: '.$url);
            }

            return true;
        }
    }
}
