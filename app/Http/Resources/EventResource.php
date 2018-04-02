<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'organiser' => new UserResource($this->whenLoaded('organiser')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'name' => (string) $this->name,
            'slug' => (string) $this->slug,
            'description' => (string) $this->description,
            'location' => (string) $this->location,
            'event_date' => (string) $this->date,
            'event_time' => (string) $this->time,
        ];
    }
}
