<?php namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Onyx\Halo5\Client;
use Onyx\Halo5\Enums\MetadataType;
use Onyx\Halo5\Objects\Enemy;
use Onyx\Halo5\Objects\Event\Metadata;
use Onyx\Halo5\Objects\Gametype;
use Onyx\Halo5\Objects\Impulse;

class updateImpulses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:impulses-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates impulses metadata.';

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
        $this->info('Getting impulses from 343');
        $impulses = $client->getImpulses();

        if (is_array($impulses))
        {
            foreach ($impulses as $impulse)
            {
                try
                {
                    $m = new Metadata();
                    $m->uuid = $impulse['id'];
                    $m->contentId = $impulse['contentId'];
                    $m->name = $impulse['internalName'];
                    $m->description = null;
                    $m->type = MetadataType::Impulses;
                    $m->save();
                }
                catch (QueryException $e)
                {

                }

                try
                {
                    $_impulse = Impulse::where('id', $impulse['id'])->firstOrFail();

                    $this->info('Enemy ' . $impulse['internalName'] . ' already exists. Updating.');
                    $_impulse->name = $impulse['internalName'];
                    $_impulse->save();
                }
                catch (ModelNotFoundException $e)
                {
                    $this->info('Adding ' . $impulse['internalName']);

                    $i = new Impulse();
                    $i->name = $impulse['internalName'];
                    $i->id = $impulse['id'];
                    $i->contentId = $impulse['contentId'];
                    $i->save();
                }
            }
        }
    }
}
