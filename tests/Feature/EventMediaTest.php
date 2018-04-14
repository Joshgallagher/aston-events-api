<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventMediaTest extends ApiTestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_organiser_can_add_images_to_an_event_they_created()
    {
        $organiser = create('User');
        create('Category');
        $event = create('Event');

        Storage::fake('/public');

        $headers = $this->createAuthHeader($organiser);

        $this->postJson('api/v1/events/'.$event->slug.'/media', [
            'image' => UploadedFile::fake()->image('test-image.jpg'),
        ], $headers);

        Storage::disk('public')->assertExists('/1');
    }

    /** @test */
    public function an_authenticated_organiser_can_delete_images_from_an_event_they_own()
    {
        $organiser = create('User');
        create('Category');
        $event = create('Event');

        Storage::fake('/public');

        $headers = $this->createAuthHeader($organiser);

        $this->postJson('api/v1/events/'.$event->slug.'/media', [
            'image' => UploadedFile::fake()->image('test-image.jpg'),
        ], $headers);

        $this->deleteJson('api/v1/events/media/1', [], $headers);

        Storage::disk('public')->assertMissing('/1');
    }
}
