<?php

namespace App\Http\Resources;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Converts the given contact number to a valid GB phone number.
     *
     *  @var string
     */
    const PHONE_COUNTRY_CODE = 'GB';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'contact_number' => (string) PhoneNumber::make($this->contact_number, self::PHONE_COUNTRY_CODE),
        ];
    }
}
