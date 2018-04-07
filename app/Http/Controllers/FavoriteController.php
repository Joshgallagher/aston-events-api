<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Response;

class FavoriteController extends Controller
{
    /**
     * Store a newly created Favorite in storage.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event)
    {
        $event->favorite();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the Favorite resource from storage.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->unfavorite();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
