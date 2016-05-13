<?php

namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\Playlist;

class updatePlaylists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:playlist-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls down playlist information into table';

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
        $this->info('Getting new Playlist data from 343');
        $playlists = $client->getPlaylists();

        if (is_array($playlists))
        {
            $this->info('We found Playlist data. Adding to table after purge.');
            foreach($playlists as $playlist)
            {
                try
                {
                    /** @var $_playlist Playlist */
                    $_playlist = Playlist::where('contentId', $playlist['id'])->firstOrFail();
                    $this->info('Playlist: ' . $playlist['name'] . ' already exists. Updating now.');

                    $_playlist->name = $playlist['name'];
                    $_playlist->description = $playlist['description'];
                    $_playlist->isRanked = $playlist['isRanked'];
                    $_playlist->isActive = $playlist['isActive'];
                    $_playlist->save();
                }
                catch (ModelNotFoundException $ex)
                {
                    $this->info('Adding ' . $playlist['name']);
                    $p = new Playlist();
                    $p->name = $playlist['name'];
                    $p->description = $playlist['description'];
                    $p->isRanked = $playlist['isRanked'];
                    $p->isActive = $playlist['isActive'];
                    $p->gameMode = $playlist['gameMode'];
                    $p->contentId = $playlist['id'];
                    $p->imageUrl = $playlist['imageUrl'];
                    $p->save();
                }
            }
        }
    }
}
