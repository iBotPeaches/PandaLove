<?php

namespace PandaLove\Console\Commands\Halo5;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Halo5\Client;
use Onyx\Halo5\Objects\CSR;

class updateCsrs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halo5:csr-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls down CSR information (should only ever need to be run once)';

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
        $this->info('Getting new CSR data from 343');
        $csrs = $client->getCsrs();

        if (is_array($csrs)) {
            $this->info('We found CSR data. Adding to table after purge.');

            foreach ($csrs as $csr) {
                try {
                    $this->info('Updating '.$csr['name']);
                    $_csr = CSR::where('designationId', $csr['id'])->firstOrFail();
                    $_csr->bannerUrl = $csr['bannerImageUrl'];
                    $_csr->tiers = $csr['tiers'];
                    $_csr->save();
                } catch (ModelNotFoundException $ex) {
                    $this->info('Adding '.$csr['name']);

                    $c = new CSR();
                    $c->designationId = $csr['id'];
                    $c->name = $csr['name'];
                    $c->bannerUrl = $csr['bannerImageUrl'];

                    $c->tiers = $csr['tiers'];
                    $c->save();
                }
            }
        }
    }
}
