<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_search_for_events()
    {
        $search = 'foobar';

        create('User');
        create('Category');
        create('Event', [], 2);
        create('Event', [
            'name' => "An event with the {$search} term",
        ], 2);

        $results = $this->getJson("api/v1/search?query={$search}")->json();

        $this->assertCount(2, $results['data']);
    }
}
