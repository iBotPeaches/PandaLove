<?php namespace Onyx\Halo5;

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
     * Seasons explained
     *
     * @var string
     */
    public static $metadata_seasons = 'https://www.haloapi.com/metadata/h5/metadata/seasons';

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
    public static $emblem_image = 'https://www.haloapi.com/profile/h5/profiles/%1$s/emblem?size=%2$d';

    /**
     * Halo5 Spartan Image URL
     *
     * @var string
     */
    public static $spartan_image = 'https://www.haloapi.com/profile/h5/profiles/%1$s/spartan?size=%2$d';
}