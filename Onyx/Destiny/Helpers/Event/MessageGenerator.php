<?php namespace Onyx\Destiny\Helpers\Event;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Destiny\Objects\Attendee;
use Onyx\Destiny\Objects\Character;
use Onyx\Destiny\Objects\GameEvent;

class MessageGenerator {

    /**
     * @param $user
     * @param $all
     * @return string
     */
    public static function buildRSVPResponse($user, $all)
    {
        $msg = '';

        // Lets check if char_id is 0, if so. Let the user know of their chars with numbers to pick one.
        if (intval($all['char_id']) == 0)
        {
            $count = 0;
            $msg = 'I need to know which character you want to be <strong>' . $user->account->gamertag . '</strong> for this event. Below are your characters with a number next to them. <br /><br />';
            foreach ($user->account->characters as $char)
            {
                $msg .= ++$count . ". - " . $char->name() . " " . $char->highest_light . "/" . $char->light . "<br />";
            }

            $msg .= '<br />Your new command will be <strong>/bot rsvp ' . $all['game_id'] . ' #</strong> Where # is one of the numbers above.';
        }
        else
        {
            // does this char even exist
            $char = $user->account->characterAtPosition($all['char_id']);

            if ($char instanceof Character)
            {
                try
                {
                    $event = GameEvent::where('id', intval($all['game_id']))->firstOrFail();

                    if ($event->isFull())
                    {
                        $msg = 'Ouch sorry man. This event is Full. No more RSVPs allowed';
                    }
                    else
                    {
                        if ($event->isAttending($user))
                        {
                            $msg = 'O think your slick eh? You are already attending this event. There is nothing you need to do.';
                        }
                        else
                        {
                            $attendee = new Attendee();
                            $attendee->game_id = $event->id;
                            $attendee->membershipId = $user->account->membershipId;
                            $attendee->characterId = $char->characterId;
                            $attendee->account_id = $user->account->id;
                            $attendee->user_id = $user->id;
                            $attendee->save();

                            $msg = 'Congrats <strong> ' . $user->account->gamertag . '</strong> you have sealed a spot in this ';
                            $msg .= '<a href="' . \URL::action('CalendarController@getEvent', [$event->id]) . '">event</a>. There are <strong>' . $event->spotsRemaining() . '</strong> spots remaining.';
                        }
                    }
                }
                catch (ModelNotFoundException $e)
                {
                    $msg = 'Sorry to break the news to you, but this event does not exist. Please try a different gameId.';
                }
            }
            else
            {
                $count = 0;
                $msg = 'Trying to be funny I see. That character does not exist for you. I guess I have to remind you. <br />';
                foreach ($user->account->characters as $char)
                {
                    $msg .= ++$count . ". - " . $char->name() . " " . $char->highest_light . "/" . $char->light . "<br />";
                }

                $msg .= '<br />. Your new command will be <strong>/bot rsvp ' . $all['game_id'] . ' #</strong> Where # is one of the numbers above.';
            }
        }

        return $msg;
    }
}