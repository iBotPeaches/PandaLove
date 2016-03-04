<?php namespace PandaLove\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Onyx\Calendar\Objects\Event as GameEvent;
use Onyx\Hangouts\Helpers\Messages;

class alertSender extends Command
{
    use DispatchesCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends alerts to games';

    /**
     * @var int
     */
    private $seconds_in_5minutes = 300;

    /**
     * @var int
     */
    private $seconds_in_15minutes = 900;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Chicago');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }
}
