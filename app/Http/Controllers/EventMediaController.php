<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class EventMediaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event)
    {
        $this->authorize('update', $event);

        $requestImage = request('image');
        $requestImageName = md5($requestImage->getClientOriginalName().microtime());
        $requestImageExtension = $requestImage->getClientOriginalExtension();
        $uniqueImageName = "{$requestImageName}.{$requestImageExtension}";

        $event->addMediaFromRequest('image')
            ->usingFileName($uniqueImageName)
            ->toMediaCollection();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media)
    {
        $this->authorize('delete', $media->model);

        $media->delete();
    }
}
