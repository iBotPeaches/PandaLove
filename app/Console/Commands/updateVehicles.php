<?php

namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Onyx\Halo5\Client;
use Onyx\Halo5\Enums\MetadataType;
use Onyx\Halo5\Objects\Event\Metadata;
use Onyx\Halo5\Objects\Vehicle;

class updateVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:vehicles-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates vehicles metadata.';

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
        $this->info('Getting vehicles from 343');
        $vehicles = $client->getVehicles();
        $path = 'public/images/vehicles/';

        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }

        if (is_array($vehicles)) {
            foreach ($vehicles as $vehicle) {
                try {
                    $m = new Metadata();
                    $m->uuid = $vehicle['id'];
                    $m->contentId = $vehicle['contentId'];
                    $m->name = $vehicle['name'];
                    $m->description = $vehicle['description'];
                    $m->type = MetadataType::Vehicle;
                    $m->save();
                } catch (QueryException $e) {
                }

                try {
                    $_vehicle = Vehicle::where('uuid', $vehicle['id'])->firstOrFail();

                    $this->info('Vehicle '.$vehicle['name'].' already exists. Updating.');
                    $_vehicle->name = $vehicle['name'];
                    $_vehicle->save();
                } catch (ModelNotFoundException $e) {
                    $this->info('Adding '.$vehicle['name']);

                    $v = new Vehicle();
                    $v->uuid = $vehicle['id'];
                    $v->contentId = $vehicle['contentId'];
                    $v->name = $vehicle['name'];
                    $v->description = $vehicle['description'];
                    $v->useableByPlayer = boolval($vehicle['isUsableByPlayer']);
                    $v->save();

                    if ($vehicle['smallIconImageUrl'] != null) {
                        if (!file_exists($path.$vehicle['id'].'.png')) {
                            $icon = file_get_contents($vehicle['smallIconImageUrl']);

                            /** @var $image \Intervention\Image\Image */
                            $image = Image::make($icon);
                            $image->save($path.$vehicle['id'].'-small.png');
                        }
                    }

                    if ($vehicle['largeIconImageUrl'] != null) {
                        if (!file_exists($path.$vehicle['id'].'.png')) {
                            $icon = file_get_contents($vehicle['largeIconImageUrl']);

                            /** @var $image \Intervention\Image\Image */
                            $image = Image::make($icon);
                            $image->save($path.$vehicle['id'].'-large.png');
                        }
                    }
                }
            }
        }
    }
}
