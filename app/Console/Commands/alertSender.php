<?php

namespace PandaLove\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Onyx\Destiny\Objects\GameEvent;
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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = GameEvent::where('start', '>=', Carbon::now('America/Chicago'))
            ->orderBy('start', 'ASC')
            ->get();

        $messenger = new Messages();

        if (count($events) > 0)
        {
            foreach($events as $event)
            {
                $this->info('Checking event: ' . $event->title);
                $diff = $event->start->diffInSeconds(Carbon::now('America/Chicago'));
                $this->info('Event happens in ' . $diff . ' seconds.');

                if (! $event->alert_15)
                {
                    if ($diff <= $this->seconds_in_15minutes)
                    {
                        $this->info('Alerting members of fireteam, that its 15 minutes before event');
                        foreach ($event->attendees as $attendee)
                        {
                            $user = $attendee->user;
                            $messenger->sendMessage($user, 'The event: <strong>' . $event->title . '</strong> starts in about 15 minutes (You RSVP`d to this Event, which is why you are being alerted).');
                        }

                        $event->alert_15 = true;
                        $event->save();
                    }
                }

                if (! $event->alert_5)
                {
                    if ($diff <= $this->seconds_in_5minutes)
                    {
                        $this->info('Alerting members of fireteam, that its 5 minutes before event.');
                        foreach ($event->attendees as $attendee)
                        {
                            $user = $attendee->user;
                            $messenger->sendMessage($user, 'The event: <strong>' . $event->title . '</strong> starts in about 5 minutes (You RSVP`d to this Event, which is why you are being alerted).');
                        }

                        $event->alert_5 = true;
                        $event->save();
                    }
                }
            }
        }
    }
}
