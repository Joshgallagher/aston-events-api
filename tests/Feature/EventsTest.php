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
        factory('App\Models\User', 15)->create();
        factory('App\Models\Category', 5)->create();
        $events = factory('App\Models\Event', 25)->create();

        $response = $this->getJson('api/v1/events')
            ->assertJsonFragment([
                'name' => $events[4]->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_user_can_view_a_specific_event()
    {
        factory('App\Models\User')->create();
        factory('App\Models\Category')->create();
        $event = factory('App\Models\Event')->create();

        $response = $this->getJson('api/v1/events/'.$event->id)
            ->assertJsonFragment([
                'name' => $event->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_specific_event_shows_its_organisers_details()
    {
        $organiser = factory('App\Models\User')->create();
        factory('App\Models\Category')->create();
        $event = factory('App\Models\Event')->create();

        $response = $this->getJson('api/v1/events/'.$event->id)
            ->assertJsonFragment([
                'name' => $organiser->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function guests_may_not_create_events()
    {
        factory('App\Models\User')->create();
        factory('App\Models\Category')->create();
        $event = factory('App\Models\Event')->make();

        $this->postJson('api/v1/events', $event->toArray(), $this->getHeaders())
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function an_authenticated_organiser_can_create_an_event()
    {
        $organiser = factory('App\Models\User')->create();
        factory('App\Models\Category')->create();
        $event = factory('App\Models\Event')->make();

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
