<?php

namespace PandaLove\Console\Commands\Halo5;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\Team;

class updateTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:teams-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates teams metadata. (Should only need to be run once)';

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
        $this->info('Getting teams from 343');

        $teams = $client->getTeams();
        $path = 'public/uploads/h5/images/teams/';

        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true);
        }

        if (is_array($teams)) {
            foreach ($teams as $team) {
                try {
                    $_team = Team::where('id', $team['id'])->firstOrFail();

                    $this->info('Team '.$team['name'].' already exists. Updating.');
                    $_team->name = $team['name'];
                    $_team->save();
                } catch (ModelNotFoundException $e) {
                    $this->info('Adding '.$team['name']);

                    $t = new Team();
                    $t->id = $team['id'];
                    $t->name = $team['name'];
                    $t->color = $team['color'];
                    $t->contentId = $team['contentId'];
                    $t->save();
                }

                if ($team['iconUrl'] != null) {
                    if (!file_exists($path.$team['id'].'.png')) {
                        $icon = file_get_contents($team['iconUrl']);

                        /** @var $image \Intervention\Image\Image */
                        $image = Image::make($icon);
                        $image->save($path.$team['id'].'.png');
                    }
                }
            }
        }
    }
}
