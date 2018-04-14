<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyEventsTest extends ApiTestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_delete_events()
    {
        create('User');
        create('Category');
        $event = create('Event');

        $this->deleteJson('api/v1/events/'.$event->slug)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('events', $event->toArray());
    }

    /** @test */
    public function unauthorized_organisers_can_not_delete_events()
    {
        $authOrganiser = create('User');
        create('Category');
        $event = create('Event', ['user_id' => create('User')->id]);

        $headers = $this->createAuthHeader($authOrganiser);

        $this->deleteJson('api/v1/events/'.$event->slug, [], $headers)
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

        $this->deleteJson('api/v1/events/'.$event->slug, [], $headers)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('events', $event->toArray());
    }
}
