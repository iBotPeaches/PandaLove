<?php namespace Onyx\Destiny\Helpers\Assets;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Onyx\Destiny\Objects\Hash;

class Images {

    /**
     * @param \Onyx\Destiny\Objects\Hash $hash
     * @param string $type
     * @return bool
     */
    public static function saveImageLocally($hash, $type = 'other')
    {
        $url = "https://bungie.net" . $hash->extra;
        $location = public_path('uploads/' . $type . '/');
        $filename = $hash->hash . "." . pathinfo($hash->extra, PATHINFO_EXTENSION);

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
