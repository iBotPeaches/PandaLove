<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Onyx\User;

class enforceTimezoneEventCreationTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public $exampleResponse = [
        'google_id'   => '112346535434889804882',
        'game'        => 'ow',
        'type'        => 'comp',
        'title'       => 'fii',
        'max_players' => 6,
        'start'       => 'August 15, 2016 5:00pm CST',
    ];

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCentralDate()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['admin' => true]);

        $this->exampleResponse['start'] = 'August 15, 2016 5:00pm CST';
        $this->exampleResponse['google_id'] = $user->google_id;

        $response = $this
            ->actingAs($user)
            ->post('/xbox/api/v1/add-event', $this->exampleResponse)
            ->seeJson(['error' => false])
            ->response->getContent();

        $json = json_decode($response, true);

        /** @var \Onyx\Calendar\Objects\Event $gameEvent */
        $gameEvent = \Onyx\Calendar\Objects\Event::find($json['id']);
        $this->assertEquals($gameEvent->botDate(), 'Aug 15 (Mon) - 5:00pm CST');
    }

    public function testEasternDaylightDate()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['admin' => true]);

        $this->exampleResponse['start'] = 'August 15, 2016 5:00pm EST';
        $this->exampleResponse['google_id'] = $user->google_id;

        $response = $this
            ->actingAs($user)
            ->post('/xbox/api/v1/add-event', $this->exampleResponse)
            ->seeJson(['error' => false])
            ->response->getContent();

        $json = json_decode($response, true);

        /** @var \Onyx\Calendar\Objects\Event $gameEvent */
        $gameEvent = \Onyx\Calendar\Objects\Event::find($json['id']);
        $this->assertEquals($gameEvent->botDate(), 'Aug 15 (Mon) - 4:00pm CST');
    }

    public function testEasternNoDaylightDate()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['admin' => true]);

        $this->exampleResponse['start'] = 'August 15, 2016 5:00pm EDT';
        $this->exampleResponse['google_id'] = $user->google_id;

        $response = $this
            ->actingAs($user)
            ->post('/xbox/api/v1/add-event', $this->exampleResponse)
            ->seeJson(['error' => false])
            ->response->getContent();

        $json = json_decode($response, true);

        /** @var \Onyx\Calendar\Objects\Event $gameEvent */
        $gameEvent = \Onyx\Calendar\Objects\Event::find($json['id']);
        $this->assertEquals($gameEvent->botDate(), 'Aug 15 (Mon) - 3:00pm CST');
    }

    public function testEvent14MinutesInFuture()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['admin' => true]);
        $date = Carbon::now(new DateTimeZone('CST'))->addMinutes(14);

        $this->exampleResponse['start'] = $date->toDateTimeString();
        $this->exampleResponse['google_id'] = $user->google_id;

        $response = $this
            ->actingAs($user)
            ->post('/xbox/api/v1/add-event', $this->exampleResponse)
            ->seeJson(['error' => false])
            ->response->getContent();

        $json = json_decode($response, true);

        /** @var \Onyx\Calendar\Objects\Event $gameEvent */
        $gameEvent = \Onyx\Calendar\Objects\Event::find($json['id']);
        $diff = Carbon::now('America/Chicago')->diffInSeconds($gameEvent->start, false);

        $this->assertTrue($diff > 600, 'This is at '. $diff);
        $this->assertEquals($gameEvent->botDate(), $date->format('M j (D) - g:ia T'));
    }
}
