<?php

namespace Tests\Unit;

use Tests\TestCase;
use Facades\App\Models\User as UserModel;

class User extends TestCase
{
    /** @test */
    public function a_generated_email_confirmation_token_has_a_length_of_100()
    {
        $this->assertEquals(strlen(UserModel::createConfirmationToken(make('User')->email)), 100);
    }
}
