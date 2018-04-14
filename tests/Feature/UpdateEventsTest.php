<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventsTest extends ApiTestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_update_events()
    {
        $authOrganiser = create('User');
        create('Category');
        $event = create('Event');

        $this->patchJson('api/v1/events/'.$event->slug, [
            'name' => 'I patched the name.',
            'description' => 'I patched the description.',
        ])->assertStatus(Response::HTTP_UNAUTHORIZED);

        tap($event->fresh(), function ($event) {
            $this->assertNotEquals('I patched the name.', $event->name);
            $this->assertNotEquals('I patched the description.', $event->description);
        });
    }

    /** @test */
    public function unauthorized_organisers_can_not_update_events()
    {
        $authOrganiser = create('User');
        create('Category');
        $event = create('Event', ['user_id' => create('User')->id]);

        $headers = $this->createAuthHeader($authOrganiser);

        $this->patchJson('api/v1/events/'.$event->slug, [
            'name' => 'I patched the name.',
            'description' => 'I patched the description.',
        ], $headers)->assertStatus(Response::HTTP_FORBIDDEN);

        tap($event->fresh(), function ($event) {
            $this->assertNotEquals('I patched the name.', $event->name);
            $this->assertNotEquals('I patched the description.', $event->description);
        });
    }

    /** @test */
    public function an_authorized_organiser_can_update_their_events()
    {
        $authOrganiser = create('User');
        create('Category');
        $event = create('Event');
        $relatedEvent = create('Event');

        $headers = $this->createAuthHeader($authOrganiser);

        $this->patchJson('api/v1/events/'.$event->slug, [
            'related_event_id' => $relatedEvent->id,
            'name' => 'I patched the name.',
            'description' => 'I patched the description.',
        ], $headers)->assertStatus(Response::HTTP_NO_CONTENT);

        tap($event->fresh(), function ($event) use ($relatedEvent) {
            $this->assertEquals($relatedEvent->id, $event->related_event_id);
            $this->assertEquals('I patched the name.', $event->name);
            $this->assertEquals('I patched the description.', $event->description);
        });
    }

    /** @test */
    public function an_updated_events_related_event_can_not_be_itself()
    {
        $authOrganiser = create('User');
        create('Category');
        $event = create('Event', [
            'related_event_id' => create('Event')->id,
        ]);

        $headers = $this->createAuthHeader($authOrganiser);

        $this->patchJson('api/v1/events/'.$event->slug, [
            'related_event_id' => $event->id,
        ], $headers)->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertNotEquals($event->fresh()->id, $event->related_event_id);
    }
}
