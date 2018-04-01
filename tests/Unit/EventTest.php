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
        factory('App\Models\User')->create();
        factory('App\Models\Category')->create();
        $event = factory('App\Models\Event')->create();

        $this->assertInstanceOf('App\Models\User', $event->organiser);
    }
}
