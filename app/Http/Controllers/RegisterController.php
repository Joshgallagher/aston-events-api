<?php

namespace App\Http\Controllers;

use Facades\App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterUserRequest;

class RegisterController extends Controller
{
    /**
     * Store a newly created User resource in storage.
     *
     * @param \App\Http\Requests\RegisterUserRequest $request
     *
     * @return \App\Http\Resources\UserResource
     */
    public function store(RegisterUserRequest $request): UserResource
    {
        $user = User::create([
            'name' => request('name'),
            'email' => $email = request('email'),
            'contact_number' => request('contact_number'),
            'password' => Hash::make(request('password')),
            'confirmation_token' => User::createConfirmationToken($email),
        ]);

        event(new Registered($user));

        $accessToken = auth()->attempt(request(['email', 'password']));
        $expiresIn = auth()->factory()->getTTL() * 60;

        return (new UserResource($user))->additional([
            'meta' => [
                'access_token' => (string) $accessToken,
                'token_type' => (string) 'bearer',
                'expires_in' => (int) $expiresIn,
            ],
        ]);
    }
}
