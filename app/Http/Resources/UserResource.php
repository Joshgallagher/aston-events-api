<?php

namespace App\Http\Resources;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
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
            'contact_number' => $this->when($this->contact_number, $this->formatContactNumber()),
        ];
    }

    /**
     * Format the given contact number to the GB standard.
     *
     * @return \Propaganistas\LaravelPhone\PhoneNumber
     */
    protected function formatContactNumber()
    {
        return PhoneNumber::make($this->contact_number, 'GB');
    }
}
