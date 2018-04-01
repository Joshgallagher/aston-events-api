<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventsTest extends TestCase
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
}
