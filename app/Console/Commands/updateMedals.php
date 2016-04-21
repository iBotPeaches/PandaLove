<?php namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Onyx\Halo5\Client;
use Onyx\Halo5\Enums\MetadataType;
use Onyx\Halo5\Objects\Event\Metadata;
use Onyx\Halo5\Objects\Medal;

class updateMedals extends Command
{
    use DispatchesCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:medal-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Halo 5 medals';

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
        $this->info('Getting new Medal data from 343');
        $medals = $client->getMedals();

        if (is_array($medals))
        {
            $this->info('We found a lot of medals. ' . count($medals) . ' to be exact.');

            $this->info('Purging table to make room for new medals');
            DB::table('halo5_medals')->truncate();

            $contents = <<<EOF
.medal {
    display: inline-block;
}\n
EOF;
            foreach($medals as $medal)
            {
                try
                {
                    $m = new Metadata();
                    $m->uuid = $medal['id'];
                    $m->contentId = $medal['contentId'];
                    $m->name = $medal['name'];
                    $m->description = $medal['description'];
                    $m->type = MetadataType::Medal;
                    $m->save();
                }
                catch (QueryException $e)
                {

                }

                $this->info('Adding: ' . $medal['name']);
                $m = new Medal();
                $m->name = $medal['name'];
                $m->description = $medal['description'];
                $m->classification = $medal['classification'];
                $m->difficulty = $medal['difficulty'];
                $m->contentId = $medal['id'];
                $m->save();

                $contents .= <<<EOF
.medal-{$medal['id']} { /* {$medal['name']} */
    background: url('/css/images/h5-medals.png') no-repeat -{$medal['spriteLocation']['left']}px -{$medal['spriteLocation']['top']}px;
    width: {$medal['spriteLocation']['width']}px;
    height: {$medal['spriteLocation']['height']}px;
}\n
EOF;
            }

            // Lets do CSS work now
            $m = $medals[0];

            $this->info('Writing new sprite file...');
            $sprite = file_get_contents($m['spriteLocation']['spriteSheetUri']);
            Storage::put('resources/images/h5-medals.png', $sprite);
            Storage::put('resources/css/h5-sprites.css', $contents);
        }
    }
}
