<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function events_belong_to_an_organiser()
    {
        create('User');
        create('Category');
        $event = create('Event');

        $this->assertInstanceOf('App\Models\User', $event->organiser);
    }

    /** @test */
    public function events_belong_to_categories()
    {
        create('User');
        create('Category');
        $event = create('Event');

        $this->assertInstanceOf('App\Models\Category', $event->category);
    }

    /** @test */
    public function an_events_slug_is_always_unique()
    {
        create('User');
        create('Category');
        $event = create('Event', [
            'name' => 'American Football Team',
        ]);
        $duplicateEvent = create('Event', [
            'name' => 'American Football Team',
        ]);

        $this->assertDatabaseHas('events', [
            'slug' => 'american-football-team',
            'slug' => 'american-football-team-2',
        ]);
    }
}
