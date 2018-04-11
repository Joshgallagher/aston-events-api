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
    public function toArray($request): array
    {
        return [
            'name' => (string) $this->name,
            'slug' => (string) $this->slug,
            'favorites_count' => (int) $this->favorites_count,
            'favorited' => (bool) $this->favorited,
            'description' => (string) $this->description,
            'location' => (string) $this->location,
            'event_date' => (string) $this->date,
            'event_time' => (string) $this->time,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'organiser' => new UserResource($this->whenLoaded('organiser')),
            'related_event' => $this->when($this->related_event_id, new self($this->whenLoaded('relatedEvent'))),
        ];
    }
}
