<?php

namespace Onyx\Calendar\Helpers\Event;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Onyx\Calendar\Objects\Attendee;
use Onyx\Calendar\Objects\Event as GameEvent;
use Onyx\Destiny\Objects\Character;
use Onyx\User;

class MessageGenerator
{
    /**
     * @param GameEvent $event
     *
     * @return string
     */
    public static function buildSingleEventResponse($event)
    {
        $msg = '<strong><a href="'.\URL::action('CalendarController@getEvent', [$event->id]).'">'.$event->title.'</a></strong><br />';
        $msg .= '<i>'.$event->botDate().'</i><br/><br />';

        $count = 1;
        foreach ($event->attendees as $attendee) {
            $msg = self::_buildSingleEventRow($count, $attendee, $event, $msg);
        }

        if (!$event->isFull()) {
            $msg .= '<br /> Remember, you can apply via <strong>/bot rsvp '.$event->id.'</strong>';
        }

        return $msg;
    }

    /**
     * @param $events
     *
     * @return string
     */
    public static function buildEventsResponse($events)
    {
        $msg = '<strong>Upcoming Events</strong><br/><br />';
        foreach ($events as $event) {
            $msg .= $event->id.') - '.'<a href="'.\URL::action('CalendarController@getEvent', [$event->id]).'">'.$event->title.'</a> ['.$event->botDate().'] - ';
            $msg .= $event->count().'/'.$event->max_players.($event->isFull() ? ' [full]' : ' slots').'<br />';
        }

        $msg .= '<br /> Remember you can RSVP to any of the above events via <strong>/bot rsvp #</strong> where # is one of the IDs above.';

        return $msg;
    }

    /**
     * @param $user User
     * @param $all array
     *
     * @return string
     */
    public static function buildRSVPResponse($user, $all)
    {
        try {
            /** @var GameEvent $event */
            $event = GameEvent::where('id', intval($all['game_id']))->firstOrFail();

            if ($event->isFull()) {
                return 'Ouch sorry man. This event is Full. No more RSVPs allowed';
            }

            if ($event->isAttending($user)) {
                return 'O think your slick eh? You are already attending this event. There is nothing you need to do.';
            }

            if ($event->isOver()) {
                return 'Sorry this event has ended. No more RSVPs are allowed.';
            }

            if (!$event->isDestiny() && !$event->isDestiny2()) {
                return self::_buildAttendeeModel($user, $event, null);
            } elseif ($event->isDestiny2()) {
                if (intval($all['char_id']) == 0) {
                    $count = 0;
                    $msg = 'I need to know which character you want to be <strong>'.$user->account->gamertag.'</strong> for this event. Below are your characters with a number next to them. <br /><br />';
                    foreach ($user->account->destiny2->getCharacters() as $char) {
                        $msg .= ++$count.'. - '.$char->name().' '.$char->max_light.'/'.$char->light.'<br />';
                    }

                    $msg .= '<br />Your new command will be <strong>/bot rsvp '.$all['game_id'].' #</strong> Where # is one of the numbers above.';

                    return $msg;
                } else {
                    // does this char even exist
                    $char = $user->account->destiny2->characterAtPosition($all['char_id']);

                    if ($char instanceof \Onyx\Destiny2\Objects\Character) {
                        return self::_buildAttendeeModel($user, $event, $char);
                    } else {
                        $count = 0;
                        $msg = 'Trying to be funny I see. That character does not exist for you. I guess I have to remind you. <br /><br />';
                        foreach ($user->account->destiny2->getCharacters() as $char) {
                            $msg .= ++$count.'. - '.$char->name().' '.$char->max_light.'/'.$char->light.'<br />';
                        }

                        $msg .= '<br />Your new command will be <strong>/bot rsvp '.$all['game_id'].' #</strong> Where # is one of the numbers above.';

                        return $msg;
                    }
                }
            } else {
                if (intval($all['char_id']) == 0) {
                    $count = 0;
                    $msg = 'I need to know which character you want to be <strong>'.$user->account->gamertag.'</strong> for this event. Below are your characters with a number next to them. <br /><br />';
                    foreach ($user->account->destiny->characters as $char) {
                        $msg .= ++$count.'. - '.$char->name().' '.$char->highest_light.'/'.$char->light.'<br />';
                    }

                    $msg .= '<br />Your new command will be <strong>/bot rsvp '.$all['game_id'].' #</strong> Where # is one of the numbers above.';

                    return $msg;
                } else {
                    // does this char even exist
                    $char = $user->account->destiny->characterAtPosition($all['char_id']);

                    if ($char instanceof Character) {
                        return self::_buildAttendeeModel($user, $event, $char);
                    } else {
                        $count = 0;
                        $msg = 'Trying to be funny I see. That character does not exist for you. I guess I have to remind you. <br /><br />';
                        foreach ($user->account->destiny->characters as $char) {
                            $msg .= ++$count.'. - '.$char->name().' '.$char->highest_light.'/'.$char->light.'<br />';
                        }

                        $msg .= '<br />Your new command will be <strong>/bot rsvp '.$all['game_id'].' #</strong> Where # is one of the numbers above.';

                        return $msg;
                    }
                }
            }
        } catch (ModelNotFoundException $e) {
            return 'Sorry to break the news to you, but this event does not exist. Please try a different gameId.';
        }
    }

    /**
     * @param User $user
     * @param GameEvent $event
     * @param $char
     *
     * @return string
     */
    private static function _buildAttendeeModel(User $user, $event, $char)
    {
        $attendee = new Attendee();
        $attendee->game_id = $event->id;

        if ($event->isDestiny()) {
            $attendee->membershipId = $user->account->destiny->membershipId;
            $attendee->characterId = $char->characterId;
        } elseif ($event->isDestiny2()) {
            $attendee->membershipId = $user->account->destiny2->membershipId;
            $attendee->characterId = $char->characterId;
        }

        $attendee->account_id = $user->account->id;
        $attendee->user_id = $user->id;
        $attendee->save();

        $msg = 'Congrats <strong> '.$user->account->gamertag.'</strong> you have sealed a spot in this ';
        $msg .= '<a href="'.\URL::action('CalendarController@getEvent', [$event->id]).'">event</a>. There are <strong>'.($event->spotsRemaining() - 1).'</strong> spots remaining.';

        return $msg;
    }

    /**
     * @param $count integer
     * @param $attendee Attendee
     * @param $event GameEvent
     * @param $msg string
     *
     * @return string
     */
    private static function _buildSingleEventRow(&$count, $attendee, $event, $msg)
    {
        if ($event->isDestiny()) {
            $msg .= $count++ . ') - <a href="' . \URL::action('Destiny\ProfileController@index', [$attendee->account->accountType, $attendee->account->seo, $attendee->character->characterId]) .
                '">' . $attendee->account->gamertag . '</a> (' . $attendee->character->name() . ')<br />';
        } elseif ($event->isDestiny2()) {
            $msg .= $count++ . ') - <a href="' . \URL::action('Destiny2\ProfileController@index', [$attendee->account->accountType, $attendee->account->seo, $attendee->d2character->characterId]) .
                '">' . $attendee->account->gamertag . '</a> (' . $attendee->d2character->nameWithLight() . ')<br />';
        } elseif ($event->isOverwatch()) {
            $msg .= $count++.') - <a href="'.\URL::action('Overwatch\ProfileController@index', [$attendee->account->seo, $attendee->account->accountType]).
                '">'.$attendee->account->gamertag.'</a> ('.$attendee->ow->totalLevel().')<br />';
        } else {
            $msg .= $count++.') - <a href="'.\URL::action('Halo5\ProfileController@index', [$attendee->account->seo]).
                '">'.$attendee->account->gamertag.'</a> (Spartan Rank: '.$attendee->h5->spartanRank.')<br />';
        }

        return $msg;
    }
}
