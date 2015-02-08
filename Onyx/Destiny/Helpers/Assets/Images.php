<?php namespace Onyx\Destiny\Helpers\Assets;

use Illuminate\Support\Facades\File;
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
     * @return bool
     */
    public static function saveImageLocally($hash, $index = 'extra')
    {
        $url = "https://bungie.net" . $hash->{$index};
        $location = public_path('uploads/thumbs/');
        $filename = $hash->hash . (($index != 'extra') ? '_bg' : null) . "." . pathinfo($hash->{$index}, PATHINFO_EXTENSION);

        if (File::isFile($location . $filename))
        {
            return true;
        }

        if ($hash instanceof Hash)
        {
            $manager = new ImageManager();
            $img = $manager->make($url);
            $img->save($location . $filename);
            return true;
        }
    }
}
