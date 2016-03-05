<?php namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Onyx\Account;
use Onyx\Destiny\Helpers\String\HashNotLocatedException;
use PandaLove\Commands\UpdateAccount;

class updatePandas extends Command
{
    use DispatchesCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pandas:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all Pandas present';

    public $inactiveCounter = 10;

    public $refreshRateInMinutes = 10;

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
        $pandas = Account::with('user', 'destiny.characters')
            ->whereHas('user', function($query)
            {
                $query->where('isPanda', true);
            })
            ->whereHas('destiny', function($query)
            {
                $query
                    ->where('grimoire', '!=', 0)
                    ->where('inactiveCounter', '<=', 10);
            })
            ->orderBy('gamertag', 'ASC')
            ->get();

        foreach ($pandas as $panda)
        {
            $this->info('Processing ' . $panda->gamertag);

            // check for 10 inactive checks
            if ($panda->inactiveCounter >= $this->inactiveCounter)
            {
                $this->info('This account has not had new data in awhile.');
                break;
            }

            $char = $panda->destiny->firstCharacter();

            if ($char->updated_at->diffInMinutes() >= $this->refreshRateInMinutes)
            {
                // update this
                try
                {
                    $this->dispatch(new UpdateAccount($panda));
                    $this->info('Stats Updated!');
                }
                catch (HashNotLocatedException $e)
                {
                    $this->error('Could not find hash value: ' . $e->getMessage());
                    $this->info('Stat update has been skipped.');
                }
            }
        }
    }
}
