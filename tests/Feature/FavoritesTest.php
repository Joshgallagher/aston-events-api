<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends ApiTestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_favorite_an_event()
    {
        create('User');
        create('Category');
        $event = create('Event');

        $this->postJson("api/v1/events/{$event->slug}/favorites")
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertCount(0, $event->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_favorite_an_event()
    {
        $user = create('User');
        create('Category');
        $event = create('Event');

        $authHeaders = $this->createAuthHeader($user);

        $this->postJson("api/v1/events/{$event->slug}/favorites", [], $authHeaders)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertCount(1, $event->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_an_event()
    {
        $user = create('User');
        create('Category');
        $event = create('Event');

        $authHeaders = $this->createAuthHeader($user);

        $this->postJson("api/v1/events/{$event->slug}/favorites", [], $authHeaders)
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertCount(1, $event->favorites);

        $this->deleteJson("api/v1/events/{$event->slug}/favorites", [], $authHeaders)
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertCount(0, $event->fresh()->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_an_event_once()
    {
        $user = create('User');
        create('Category');
        $event = create('Event');

        $authHeaders = $this->createAuthHeader($user);

        $this->postJson("api/v1/events/{$event->slug}/favorites", [], $authHeaders);
        $this->postJson("api/v1/events/{$event->slug}/favorites", [], $authHeaders);

        $this->assertCount(1, $event->favorites);
    }
}
