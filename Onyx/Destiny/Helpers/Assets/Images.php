<?php namespace Onyx\Destiny\Helpers\Assets;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;
use Onyx\Destiny\Objects\Hash;

class Images {

    /**
     * @param \Onyx\Destiny\Objects\Hash $hash
     * @return bool
     */
    public static function saveImagesLocally($hash)
    {
        Images::saveImageLocally($hash, 'extra');

        // check for extraSecondary
        if ($hash->extraSecondary != null && ! str_contains($hash->extraSecondary, 'missing_icon'))
        {
            Images::saveImageLocally($hash, 'extraSecondary');
        }
    }

    /**
     * @param \Onyx\Destiny\Objects\Hash $hash
     * @param string $index
     * @return bool
     */
    public static function saveImageLocally($hash, $index = 'extra')
    {
        // BUG: Can't use variable object indexes implicitly
        // $hash->{$index} should work but doesn't
        // map the index explicitly with the attributes dumped into $bug
        $bug = $hash->getAttributes();
        $url = "https://bungie.net" . $bug[$index];

        $location = public_path('uploads/thumbs/');
        $filename = $hash->hash . (($index != 'extra') ? '_bg' : null) . "." . pathinfo($bug[$index], PATHINFO_EXTENSION);

        if (File::isFile($location . $filename))
        {
            return true;
        }

        if ($hash instanceof Hash)
        {
            $manager = new ImageManager();
            try
            {
                $img = $manager->make($url);
                $img->save($location . $filename);
            }
            catch (NotReadableException $e)
            {
                Log::error('Could not download: ' . $url);
            }
            return true;
        }
    }
}
