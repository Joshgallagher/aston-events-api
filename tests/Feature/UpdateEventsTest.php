<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateEventsTest extends ApiTestCase
{
    use DatabaseMigrations;

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

        $headers = $this->createAuthHeader($authOrganiser);

        $this->patchJson('api/v1/events/'.$event->slug, [
            'name' => 'I patched the name.',
            'description' => 'I patched the description.',
        ], $headers)->assertStatus(Response::HTTP_NO_CONTENT);

        tap($event->fresh(), function ($event) {
            $this->assertEquals('I patched the name.', $event->name);
            $this->assertEquals('I patched the description.', $event->description);
        });
    }
}
