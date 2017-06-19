<?php

namespace PandaLove\Console\Commands\Halo5;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\Map;

class updateMaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:maps-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Halo 5 map metadata.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $this->info('Getting maps from 343.');
        $maps = $client->getMaps();
        $path = 'public/uploads/h5/images/maps/';

        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }

        if (is_array($maps)) {
            foreach ($maps as $map) {
                try {
                    $_map = Map::where('uuid', $map['id'])->firstOrFail();

                    $this->info('Found map '.$_map['name'].'. Updating.');
                    $_map->name = $map['name'];
                    $_map->description = $map['description'];
                    $_map->game_modes = $map['supportedGameModes'];
                    $_map->save();
                } catch (ModelNotFoundException $e) {
                    $this->info('Adding map '.$map['name']);

                    $m = new Map();
                    $m->uuid = $map['id'];
                    $m->contentId = $map['contentId'];
                    $m->name = $map['name'];
                    $m->description = $map['description'];
                    $m->game_modes = $map['supportedGameModes'];

                    $m->save();
                }

                if ($map['imageUrl'] != null) {
                    if (!file_exists($path.$map['id'].'.jpg')) {
                        $icon = file_get_contents($map['imageUrl']);

                        /** @var $image \Intervention\Image\Image */
                        $image = Image::make($icon);
                        $image->save($path.$map['id'].'.jpg');
                    }
                }
            }
        }
    }
}
