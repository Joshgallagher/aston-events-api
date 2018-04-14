<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_category_has_many_events()
    {
        create('User');
        $category = create('Category');
        $event = create('Event');

        $this->assertTrue($category->events->contains($event));
    }
}
