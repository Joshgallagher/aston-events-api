<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventsTest extends ApiTestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_events()
    {
        create('User', [], 15);
        create('Category', [], 5);
        $events = create('Event', [], 25);

        $response = $this->getJson('api/v1/events')
            ->assertJsonFragment([
                'name' => $events[4]->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_user_can_view_a_specific_event()
    {
        create('User');
        create('Category');
        $event = create('Event');

        $response = $this->getJson('api/v1/events/'.$event->slug)
            ->assertJsonFragment([
                'name' => $event->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function an_authenticated_organiser_can_see_their_created_events()
    {
        $authOrganiser = create('User');
        create('Category');
        $eventByAuthOrganiser = create('Event', ['user_id' => $authOrganiser->id]);
        $eventNotByAuthOrganiser = create('Event', ['user_id' => create('User')->id]);

        $headers = $this->createAuthHeader($authOrganiser);

        $this->getJson('api/v1/events?my=1', $headers)
            ->assertJsonFragment([
                'name' => $eventByAuthOrganiser->name,
                'name' => $authOrganiser->name,
            ])
            ->assertJsonMissingExact([
                'name' => $eventNotByAuthOrganiser->name,
                'name' => $eventNotByAuthOrganiser->organiser->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function users_can_filter_events_by_their_date()
    {
        $today = Carbon::today();

        create('User');
        create('Category');
        $firstEventToday = create('Event', ['date' => $today->format('Y-m-d')]);
        $secondEventToday = create('Event', ['date' => $today->addHour(1)->format('Y-m-d')]);
        $eventTomorrow = create('Event', ['date' => Carbon::tomorrow()->format('Y-m-d')]);

        $this->getJson('api/v1/events?today=1')
            ->assertJsonFragment([
                'name' => $firstEventToday->name,
                'name' => $secondEventToday->name,
            ])
            ->assertJsonMissingExact([
                'name' => $eventTomorrow->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function users_can_filter_events_by_their_favorite_count()
    {
        create('User');
        create('Category');
        $secondEvent = create('Event');
        create('Favorite', [
            'favorited_id' => $secondEvent->id,
        ], 3);
        $firstEvent = create('Event');
        create('Favorite', [
            'favorited_id' => $firstEvent->id,
        ], 5);
        $thirdEvent = create('Event');
        create('Favorite', [
            'favorited_id' => $thirdEvent->id,
        ], 1);

        $this->getJson('api/v1/events?popular=1')
            ->assertSeeTextInOrder([
                $firstEvent->name,
                $secondEvent->name,
                $thirdEvent->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function an_authenticated_user_can_see_all_their_favorited_events()
    {
        $user = create('User');
        create('Category');

        $firstEvent = create('Event');
        create('Favorite', [
            'user_id' => $user->id,
            'favorited_id' => $firstEvent->id,
        ]);
        $secondEvent = create('Event');
        create('Favorite', [
            'user_id' => $user->id,
            'favorited_id' => $secondEvent->id,
        ]);

        create('Favorite', [
            'favorited_id' => create('Event')->id,
        ], 2);
        create('Favorite', [
            'favorited_id' => create('Event')->id,
        ]);

        $headers = $this->createAuthHeader($user);

        $results = $this->getJson('api/v1/events?favorited=1', $headers)->json()['data'];

        $this->assertCount(2, $results);
    }
}
