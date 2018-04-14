<?php

namespace Tests\Unit;

use Tests\TestCase;
use Facades\App\Models\User;

class UserTest extends TestCase
{
    /** @test */
    public function a_generated_email_confirmation_token_has_a_length_of_100()
    {
        $this->assertEquals(strlen(User::createConfirmationToken(make('User')->email)), 100);
    }
}
