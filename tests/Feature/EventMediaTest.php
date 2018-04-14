<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventMediaTest extends ApiTestCase
{
    use RefreshDatabase;

    /** Setup EventMediaTest */
    protected function setUp()
    {
        parent::setUp();

        $this->organiser = create('User');
        create('Category');
        $this->event = create('Event');
    }

    /** @test */
    public function an_authenticated_organiser_can_add_images_to_an_event_they_created()
    {
        Storage::fake('/public');

        $headers = $this->createAuthHeader($this->organiser);

        $this->postJson('api/v1/events/'.$this->event->slug.'/media', [
            'image' => UploadedFile::fake()->image('test-image.jpg'),
        ], $headers);

        Storage::disk('public')->assertExists('/1');
    }

    /** @test */
    public function an_authenticated_organiser_can_delete_images_from_an_event_they_own()
    {
        Storage::fake('/public');

        $headers = $this->createAuthHeader($this->organiser);

        $this->postJson('api/v1/events/'.$this->event->slug.'/media', [
            'image' => UploadedFile::fake()->image('test-image.jpg'),
        ], $headers);

        $this->deleteJson('api/v1/events/media/1', [], $headers);

        Storage::disk('public')->assertMissing('/1');
    }

    /** @test */
    public function only_png_jpeg_and_bmp_images_are_accepted()
    {
        $headers = $this->createAuthHeader($this->organiser);

        $this->postJson('api/v1/events/'.$this->event->slug.'/media', [
            'image' => UploadedFile::fake()->image('test-file.txt'),
        ], $headers)->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
