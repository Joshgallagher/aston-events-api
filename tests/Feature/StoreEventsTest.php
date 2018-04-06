<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StoreEventsTest extends ApiTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_can_not_create_events()
    {
        create('User');
        create('Category');
        $event = make('Event');

        $this->postJson('api/v1/events', $event->toArray(), $this->getHeaders())
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseMissing('events', $event->toArray());
    }

    /** @test */
    public function organisers_without_a_contact_number_can_not_create_events()
    {
        $organiser = create('User');
        create('Category');
        $event = make('Event');

        $headers = $this->createAuthHeader($organiser);

        $this->postJson('api/v1/events', $event->toArray(), $headers)
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('events', $event->toArray());
    }

    /** @test */
    public function organisers_with_a_contact_number_can_create_events()
    {
        $organiser = create('User', [
            'contact_number' => '07387074668',
        ]);
        create('Category');
        $event = make('Event');

        $headers = $this->createAuthHeader($organiser);

        $this->postJson('api/v1/events', $event->toArray(), $headers)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('events', $event->toArray());
    }

    /** @test */
    public function new_events_can_have_a_related_event()
    {
        $organiser = create('User', [
            'contact_number' => '07387074668',
        ]);
        create('Category');
        $relatedEvent = create('Event');
        $event = make('Event', [
            'related_event_id' => $relatedEvent->id,
        ]);

        $headers = $this->createAuthHeader($organiser);

        $this->postJson('api/v1/events', $event->toArray(), $headers)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('events', [
            'related_event_id' => null,
            'related_event_id' => $relatedEvent->id,
        ]);
    }
}
