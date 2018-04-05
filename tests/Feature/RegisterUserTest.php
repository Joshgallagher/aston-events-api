<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterUserTest extends ApiTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_register_only_with_an_aston_email()
    {
        $user = make('User', [
            'email' => 'josh@aston.ac.uk',
        ]);

        $this->postJson('api/v1/register', array_merge($user->toArray(), ['password' => 'secret']))
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', $user->toArray());
    }

    /** @test */
    public function the_user_can_only_register_with_an_aston_email()
    {
        $user = make('User', [
            'email' => 'josh@gmail.com',
        ]);

        $this->postJson('api/v1/register', array_merge($user->toArray(), ['password' => 'secret']))
            ->assertJsonFragment([
                'email' => [
                    'This is not a valid Aston University email.',
                ],
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $user = make('User', [
            'email' => 'josh@aston.ac.uk',
        ]);

        $this->postJson('api/v1/register', array_merge($user->toArray(), ['password' => 'secret']))
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function a_user_recieves_a_valid_access_token_after_registration()
    {
        $user = make('User', [
            'email' => 'josh@aston.ac.uk',
        ]);

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
