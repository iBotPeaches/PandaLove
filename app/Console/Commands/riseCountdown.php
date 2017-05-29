<?php

namespace PandaLove\Console\Commands;

use Illuminate\Console\Command;
use Onyx\Destiny\Helpers\Bot\MessageGenerator;
use Onyx\Hangouts\Helpers\Messages;

class riseCountdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destiny:countdown';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows countdown for Rise of Iron nightly';

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
        $messenger = new Messages();

        $messenger->sendGroupMessage(MessageGenerator::riseOfIronCountdown());
    }
}
