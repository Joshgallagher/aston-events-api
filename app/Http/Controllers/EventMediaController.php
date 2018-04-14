<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\Models\Media;
use App\Http\Requests\StoreEventMediaRequest;

class EventMediaController extends Controller
{
    /**
     * Store a newly created Media resource in storage.
     *
     * @param \App\Http\Requests\StoreEventMediaRequest $request
     * @param \App\Models\Event                         $event
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventMediaRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $image = request('image');
        $imageOriginalName = $image->getClientOriginalName();
        $imageOriginalExtension = ".{$image->getClientOriginalExtension()}";
        $hashName = md5($imageOriginalName.microtime());

        $event->addMediaFromRequest('image')
            ->usingName($hashName)
            ->usingFileName($hashName.$imageOriginalExtension)
            ->toMediaCollection();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified Media resource from storage.
     *
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media)
    {
        $this->authorize('delete', $media->model);

        $media->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
