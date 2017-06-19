<?php

namespace PandaLove\Console\Commands\Halo5;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\Season;
use Onyx\Halo5\Objects\SeasonPlaylist;

class updateSeasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:season-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls down season information into table';

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
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $this->info('Getting new Season data from 343');
        $seasons = $client->getSeasons();

        if (is_array($seasons)) {
            $this->info('We found Season data. Adding to table after purge.');

            foreach ($seasons as $season) {
                try {
                    $this->info('Season:  '.$season['name'].' already exists. Updating `end_date` and `is_active`.');

                    /** @var $_season Season */
                    $_season = Season::where('contentId', $season['id'])->firstOrFail();
                    $_season->name = $season['name'];
                    $_season->end_date = ($season['endDate'] == null ? new Carbon('December 31, 2020') : $season['endDate']);
                    $_season->isActive = boolval($season['isActive']);
                    $_season->save();

                    if (isset($season['playlists']) && is_array($season['playlists'])) {
                        foreach ($season['playlists'] as $playlist) {
                            try {
                                $_link = new SeasonPlaylist();
                                $_link->seasonId = $season['id'];
                                $_link->playlistId = $playlist['contentId'];
                                $_link->save();

                                $this->info('Linking '.$season['name'].' to playlist: '.$playlist['name']);
                            } catch (\Exception $e) {
                                // ignored
                            }
                        }
                    }
                } catch (ModelNotFoundException $e) {
                    $this->info('Adding '.$season['name']);

                    $s = new Season();
                    $s->name = $season['name'];
                    $s->isActive = boolval($season['isActive']);
                    $s->contentId = $season['id'];
                    $s->start_date = $season['startDate'];
                    $s->end_date = ($season['endDate'] == null ? new Carbon('December 31, 2020') : $season['endDate']);
                    $s->save();

                    if (isset($season['playlists']) && is_array($season['playlists'])) {
                        foreach ($season['playlists'] as $playlist) {
                            $_link = new SeasonPlaylist();
                            $_link->seasonId = $season['id'];
                            $_link->playlistId = $playlist['contentId'];
                            $_link->save();
                        }
                    }
                }
            }
        }
    }
}
