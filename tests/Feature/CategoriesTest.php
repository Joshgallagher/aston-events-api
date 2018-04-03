<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoriesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_listing_of_all_categories_is_available()
    {
        $categories = create('Category', [], 5);
        $categoryNames = array_column($categories->toArray(), 'name');

        $this->getJson('api/v1/categories')
            ->assertSeeInOrder($categoryNames)
            ->assertStatus(Response::HTTP_OK);
    }
}
