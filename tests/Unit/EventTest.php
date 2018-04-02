<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_an_organiser()
    {
        create('User');
        create('Category');
        $event = create('Event');

        $this->assertInstanceOf('App\Models\User', $event->organiser);
    }

    /** @test */
    public function it_belongs_to_a_category()
    {
        create('User');
        create('Category');
        $event = create('Event');

        $this->assertInstanceOf('App\Models\Category', $event->category);
    }
}
