<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesTest extends ApiTestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_listing_of_all_categories_is_available()
    {
        $categories = create('Category', [], 5);
        $categoryNames = array_column($categories->toArray(), 'name');

        $this->getJson('api/v1/categories')
            ->assertSeeInOrder($categoryNames)
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_user_can_filter_events_according_to_its_category()
    {
        create('User');
        $category = create('Category');
        $eventInCategory = create('Event', ['category_id' => $category->id]);
        $eventNotInCategory = create('Event', ['category_id' => create('Category')->id]);

        $this->getJson('api/v1/categories/'.$category->slug)
            ->assertJsonFragment([
                'name' => $eventInCategory->name,
            ])
            ->assertJsonMissingExact([
                'name' => $eventNotInCategory->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function an_authenticated_organiser_can_see_their_created_events_by_category()
    {
        $authOrganiser = create('User');
        $category = create('Category', [
            'name' => 'Test Category',
        ]);
        $eventByAuthOrganiser = create('Event', ['user_id' => $authOrganiser->id]);
        $eventNotByAuthOrganiser = create('Event', ['user_id' => create('User')->id]);

        $differentCategory = create('Category');
        $eventInDifferentCategory = create('Event', [
            'user_id' => $authOrganiser->id,
            'category_id' => $differentCategory->id,
        ]);

        $headers = $this->createAuthHeader($authOrganiser);

        $this->getJson("api/v1/categories/{$category->slug}?my=1", $headers)
            ->assertJsonFragment([
                'name' => $eventByAuthOrganiser->name,
            ])
            ->assertJsonMissingExact([
                'name' => $eventNotByAuthOrganiser->name,
                'name' => $eventInDifferentCategory->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function users_can_filter_events_in_their_category_by_their_date()
    {
        $today = Carbon::today();

        create('User');
        $category = create('Category', [
            'name' => 'Test Category',
        ]);
        $firstEventToday = create('Event', ['date' => $today->format('Y-m-d')]);
        $secondEventToday = create('Event', ['date' => $today->addHour(1)->format('Y-m-d')]);
        $eventTomorrow = create('Event', ['date' => Carbon::tomorrow()->format('Y-m-d')]);

        $this->getJson("api/v1/categories/{$category->slug}?today=1")
            ->assertJsonFragment([
                'name' => $firstEventToday->name,
                'name' => $secondEventToday->name,
            ])
            ->assertJsonMissingExact([
                'name' => $eventTomorrow->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }
}
