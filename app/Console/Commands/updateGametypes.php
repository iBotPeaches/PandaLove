<?php namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\Facades\Image;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\Gametype;

class updateGametypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:gametypes-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates gametypes metadata.';

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
        $this->info('Getting base game types from 343');
        $gametypes = $client->getGametypes();

        if (is_array($gametypes))
        {
            foreach ($gametypes as $gametype)
            {
                try
                {
                    $_gametype = Gametype::where('uuid', $gametype['id'])->firstOrFail();

                    $this->info('Gametype ' . $_gametype['name'] . ' already exists. Updating.');
                    $_gametype->name = $gametype['name'];
                    $_gametype->internal_name = $gametype['internalName'];
                    $_gametype->game_modes = $gametype['supportedGameModes'];
                    $_gametype->save();
                }
                catch (ModelNotFoundException $e)
                {
                    $this->info('Adding ' . $gametype['name']);

                    $g = new Gametype();
                    $g->name = $gametype['name'];
                    $g->internal_name = $gametype['internalName'];
                    $g->game_modes = $gametype['supportedGameModes'];
                    $g->uuid = $gametype['id'];
                    $g->contentId = $gametype['contentId'];
                    $g->save();

                    if ($gametype['iconUrl'] != null)
                    {
                        $path = 'public/images/gametypes/';

                        if (! file_exists($path . $gametype['id'] . '.png'))
                        {
                            $icon = file_get_contents($gametype['iconUrl']);

                            /** @var $image \Intervention\Image\Image */
                            $image = Image::make($icon);
                            $image->save($path . $gametype['id'] . '.png');
                        }
                    }
                }
            }
        }
    }
}
