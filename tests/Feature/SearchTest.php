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
     * WARNING: This test will fail without Algolia API credentials.
     * WARNING: This test may fail when running the whole test suite due to latency with the Algolia service.
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

            try {
                $results = $this->getJson("api/v1/search?query={$searchTerm}")->json()['data'];
            } catch (\Exception $e) {
                $this->fail('This test may have failed due to latency issues between the Algolia service or because no Algolia credentials where provided in the .env file. Please check this test file for more information.');
            }
        } while (empty($results));

        $this->assertCount(2, $results);

        Event::latest()->take(4)->unsearchable();
    }
}
