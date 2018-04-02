<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventsTest extends ApiTestCase
{
    use DatabaseMigrations;

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

        $response = $this->getJson('api/v1/events/'.$event->id)
            ->assertJsonFragment([
                'name' => $event->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function guests_may_not_create_events()
    {
        create('User');
        create('Category');
        $event = make('Event');

        $this->postJson('api/v1/events', $event->toArray(), $this->getHeaders())
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function an_authenticated_organiser_can_create_an_event()
    {
        $organiser = create('User');
        create('Category');
        $event = make('Event');

        $headers = $this->createAuthHeader($organiser);

        $this->postJson('api/v1/events', $event->toArray(), $headers)
            ->assertStatus(Response::HTTP_CREATED);

        $this->getJson('api/v1/events/'.$event->id)
            ->assertJsonFragment([
                'name' => $event->name,
                'description' => $event->description,
                'location' => $event->location,
                'event_date' => $event->date,
                'event_time' => $event->time,
            ])
            ->assertStatus(Response::HTTP_OK);
    }
}
