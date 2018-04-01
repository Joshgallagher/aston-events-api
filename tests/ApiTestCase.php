<?php

namespace Tests;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableUser;

abstract class ApiTestCase extends TestCase
{
    /**
     * Creates an Authorization header and assigns a valid JWT token
     * generated from the given User.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string|null                                $driver
     *
     * @return array
     */
    public function createAuthHeader(AuthenticatableUser $user, string $driver = null): array
    {
        $accessToken = JWTAuth::fromUser($user);

        JWTAuth::setToken($accessToken);

        return [
            'Authorization' => 'Bearer '.$accessToken,
        ];
    }
}
