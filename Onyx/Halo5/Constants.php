<?php namespace Halo5\Destiny;

class Constants{

    /**
     * Halo 5 Playlist history
     *
     * @var string
     */
    public static $metadata_playlist = 'https://www.haloapi.com/metadata/h5/metadata/playlists';

    /**
     * Halo5 Medal explanation
     *
     * @var string
     */
    public static $metadata_medals = 'https://www.haloapi.com/metadata/h5/metadata/medals';

    /**
     * CSR levels explained
     *
     * @var string
     */
    public static $metadata_csr = 'https://www.haloapi.com/metadata/h5/metadata/csr-designations';

    /**
     * Halo5 Arena stats, all-time.
     *
     * @var string
     */
    public static $servicerecord_arena = 'https://www.haloapi.com/stats/h5/servicerecords/arena?players=%s';

    /**
     * Halo5 Emblem URL
     *
     * @var string
     */
    public static $emblem_image = 'https://www.haloapi.com/profile/h5/profiles/%1$s/emblem/%2$s';

    /**
     * Halo5 Spartan Image URL
     *
     * @var string
     */
    public static $spartan_image = 'https://www.haloapi.com/profile/h5/profiles/%1$s/spartan/%2$d';
}