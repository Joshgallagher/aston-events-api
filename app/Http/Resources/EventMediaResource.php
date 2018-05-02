<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventMediaResource extends JsonResource
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
            'id' => (int) $this->id,
            'size' => (string) $this->size,
            'media_url' => (string) $this->getUrl('event-media'),
            'mime_type' => (string) $this->mime_type,
        ];
    }
}
