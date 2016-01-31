<?php namespace PandaLove\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\Season;

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

        if (is_array($seasons))
        {
            $this->info('We found Season data. Adding to table after purge.');

            DB::table('halo5_seasons')->truncate();
            foreach($seasons as $season)
            {
                $this->info('Adding ' . $season['name']);

                $s = new Season();
                $s->name = $season['name'];
                $s->isActive = boolval($season['isActive']);
                $s->contentId = $season['id'];
                $s->startDate = $season['startDate'];
                $s->endDate = $season['endDate'];
                $s->save();
            }
        }
    }
}
