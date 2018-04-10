<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\ApiTestCase;
use Illuminate\Http\Response;
use App\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterUserTest extends ApiTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_need_an_aston_email_to_register()
    {
        $user = make('User', [
            'email' => 'josh@aston.ac.uk',
        ]);

        $this->postJson('api/v1/register', array_merge($user->toArray(), ['password' => 'secret']))
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', $user->toArray());
    }

    /** @test */
    public function users_without_an_aston_email_can_not_register()
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
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $user = create('User', [
            'email' => 'josh@aston.ac.uk',
        ]);

        event(new Registered($user));

        Mail::assertSent(EmailConfirmation::class);
    }

    /** @test */
    public function users_can_confirm_their_email_addresses()
    {
        $user = make('User', [
            'email' => 'josh@aston.ac.uk',
        ]);

        $this->postJson('api/v1/register', array_merge($user->toArray(), ['password' => 'secret']))
            ->assertStatus(Response::HTTP_CREATED);

        $newUser = User::whereEmail('josh@aston.ac.uk')->first();

        $this->assertFalse($newUser->confirmed);
        $this->assertNotNull($newUser->confirmation_token);
    }

    /** @test */
    public function upon_successful_registration_a_valid_token_is_returned()
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
