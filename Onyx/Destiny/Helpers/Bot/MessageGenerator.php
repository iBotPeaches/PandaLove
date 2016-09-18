<?php namespace Onyx\Destiny\Helpers\Bot;

use Carbon\Carbon;
use Onyx\Laravel\Helpers\Text;

class MessageGenerator {

    /**
     * @return string
     */
    public static function riseOfIronCountdown()
    {
        $now = Carbon::now('America/Chicago');
        $rise = Carbon::parse('September 20 2016 4:00am', 'America/Chicago');

        if ($now > $rise)
        {
            return "RISE OF IRON IS LIVE. You best be playin or SIVA will get you.";
        }

        $countdown = $rise->diffInSeconds($now);
        $countdown = Text::timeDuration($countdown);

        return "Rise Of Iron arrives in: <strong>" . $countdown . "</strong>.";
    }
}
