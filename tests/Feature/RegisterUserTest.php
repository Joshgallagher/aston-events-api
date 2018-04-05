<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterUserTest extends ApiTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_register()
    {
        $user = make('User');

        $this->postJson('api/v1/register', array_merge($user->toArray(), ['password' => 'secret']))
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', $user->toArray());
    }

    /** @test */
    public function all_fields_are_required_to_register()
    {
        $user = make('User');

        $this->postJson('api/v1/register', $user->toArray())
            ->assertJsonFragment([
                'password' => [
                    'The password field is required.',
                ],
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function a_user_recieves_a_valid_access_token_after_registration()
    {
        $user = make('User');

        $response = $this->postJson('api/v1/register', array_merge($user->toArray(), ['password' => 'secret']))
            ->assertStatus(Response::HTTP_CREATED);

        $accessToken = $response->getData()->meta->access_token;
        $headers = array_merge($this->getHeaders(), ['Authorization' => $accessToken]);

        $this->getJson('api/v1/user', $headers)
            ->assertJsonFragment([
                'name' => $user->name,
            ])
            ->assertStatus(Response::HTTP_OK);
    }
}
