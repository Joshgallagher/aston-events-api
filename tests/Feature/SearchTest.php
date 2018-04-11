<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * NOTE: This test may fail when running the whole test suite.
     *
     * This is because this test hits the Algolia API and due to latency
     * it may fail. If this is the case, please run this test on it's own.
     */
    public function a_user_can_search_for_events()
    {
        config(['scout.driver' => 'algolia']);

        $searchTerm = 'foobar';

        create('User');
        create('Category');
        create('Event', [], 2);
        create('Event', [
            'name' => "An event with the {$searchTerm} term",
        ], 2);

        do {
            sleep(.25);

            $results = $this->getJson("api/v1/search?query={$searchTerm}")->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        Event::latest()->take(4)->unsearchable();
    }
}
