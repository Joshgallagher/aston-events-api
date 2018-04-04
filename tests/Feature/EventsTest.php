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
    public function organisers_must_have_a_contact_number_to_create_events()
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
    public function a_user_can_filter_events_according_to_its_category()
    {
        create('User');
        $category = create('Category');
        $eventInCategory = create('Event', ['category_id' => $category->id]);
        $eventNotInCategory = create('Event', ['category_id' => create('Category')->id]);

        $this->getJson('api/v1/categories/'.$category->slug)
            ->assertJsonFragment([
                'name' => $eventInCategory->name,
            ])
            ->assertJsonMissingExact([
                'name' => $eventNotInCategory->name,
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
        $today = \Carbon\Carbon::today();

        create('User');
        create('Category');
        $firstEventToday = create('Event', ['date' => $today->format('Y-m-d')]);
        $secondEventToday = create('Event', ['date' => $today->addHour(1)->format('Y-m-d')]);
        $eventTomorrow = create('Event', ['date' => \Carbon\Carbon::tomorrow()->format('Y-m-d')]);

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
    public function guests_cannot_delete_events()
    {
        create('User');
        create('Category');
        $event = create('Event');

        $this->deleteJson('api/v1/events/'.$event->id)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('events', $event->toArray());
    }

    /** @test */
    public function unauthorized_organisers_cannot_delete_events()
    {
        $authOrganiser = create('User');
        create('Category');
        $event = create('Event', ['user_id' => create('User')->id]);

        $headers = $this->createAuthHeader($authOrganiser);

        $this->deleteJson('api/v1/events/'.$event->id, [], $headers)
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('events', $event->toArray());
    }

    /** @test */
    public function an_authorized_organiser_can_delete_their_events()
    {
        $authOrganiser = create('User');
        create('Category');
        $event = create('Event');

        $headers = $this->createAuthHeader($authOrganiser);

        $this->deleteJson('api/v1/events/'.$event->id, [], $headers)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('events', $event->toArray());
    }
}
