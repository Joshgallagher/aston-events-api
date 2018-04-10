<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to another type.
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Create an email confirmation token from the User's email and random string.
     *
     * @param string $email
     *
     * @return string
     */
    public function createConfirmationToken(string $email): string
    {
        return md5($email).str_random(68);
    }

    /**
     * Confirm that the User's email is correct.
     *
     * @return bool
     */
    public function confirm(): bool
    {
        $this->confirmed = true;
        $this->confirmation_token = null;

        $this->save();
    }
}
