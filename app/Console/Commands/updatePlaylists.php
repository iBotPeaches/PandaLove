<?php

namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
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
            $this->info('We found CSR data. Adding to table after purge.');

            DB::table('halo5_playlists')->truncate();
            foreach($playlists as $playlist)
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
