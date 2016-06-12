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
        $rise = Carbon::parse('September 20 2016', 'America/Chicago');

        $countdown = $rise->diffInSeconds($now);
        $countdown = Text::timeDuration($countdown);

        return "Rise Of Iron arrives in: <strong>" . $countdown . "</strong>. <br />Pre-order: <a href='http://bit.ly/1YjjvBW'>here.</a>";
    }
}
