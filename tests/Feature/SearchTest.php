<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_search_for_events()
    {
        config(['scout.driver' => 'algolia']);

        $search = 'foobar';

        create('User');
        create('Category');
        create('Event', [], 2);
        create('Event', [
            'name' => "An event with the {$search} term",
        ], 2);

        do {
            sleep(.25);

            $results = $this->getJson("api/v1/search?query={$search}")->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        Event::latest()->take(4)->unsearchable();
    }
}
