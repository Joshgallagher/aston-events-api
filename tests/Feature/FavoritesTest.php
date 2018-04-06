<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends ApiTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_favorite_any_event()
    {
        $user = create('User');
        create('Category');
        $event = create('Event');

        $authHeaders = $this->createAuthHeader($user);

        $this->postJson("api/v1/events/{$event->slug}/favorites", [], $authHeaders);

        $this->assertCount(1, $event->favorites);
    }
}
