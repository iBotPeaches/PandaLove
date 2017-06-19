<?php

namespace PandaLove\Console\Commands\Halo5;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Onyx\Halo5\Client;
use Onyx\Halo5\Enums\MetadataType;
use Onyx\Halo5\Objects\Enemy;
use Onyx\Halo5\Objects\Event\Metadata;

class updateEnemies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:enemies-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates enemies metadata.';

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
        $this->info('Getting enemies from 343');
        $enemies = $client->getEnemies();
        $path = 'public/uploads/h5/images/enemies/';

        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }

        if (is_array($enemies)) {
            foreach ($enemies as $enemy) {
                try {
                    $m = new Metadata();
                    $m->uuid = $enemy['id'];
                    $m->contentId = $enemy['contentId'];
                    $m->name = $enemy['name'];
                    $m->description = $enemy['description'];
                    $m->type = MetadataType::Enemy;
                    $m->save();
                } catch (QueryException $e) {
                    // ignored
                }

                try {
                    $_enemy = Enemy::where('id', $enemy['id'])->firstOrFail();

                    $this->info('Enemy '.$enemy['name'].' already exists. Updating.');
                    $_enemy->name = $enemy['name'];
                    $_enemy->save();
                } catch (ModelNotFoundException $e) {
                    $this->info('Adding '.$enemy['name']);

                    $e = new Enemy();
                    $e->id = $enemy['id'];
                    $e->contentId = $enemy['contentId'];
                    $e->faction = $enemy['faction'];
                    $e->name = $enemy['name'];
                    $e->description = $enemy['description'];
                    $e->save();
                }

                if ($enemy['smallIconImageUrl'] != null) {
                    if (!file_exists($path.$enemy['id'].'.png')) {
                        $icon = file_get_contents($enemy['smallIconImageUrl']);

                        /** @var $image \Intervention\Image\Image */
                        $image = Image::make($icon);
                        $image->save($path.$enemy['id'].'-small.png');
                    }
                }

                if ($enemy['largeIconImageUrl'] != null) {
                    if (!file_exists($path.$enemy['id'].'.png')) {
                        $icon = file_get_contents($enemy['largeIconImageUrl']);

                        /** @var $image \Intervention\Image\Image */
                        $image = Image::make($icon);
                        $image->save($path.$enemy['id'].'-large.png');
                    }
                }
            }
        }
    }
}
