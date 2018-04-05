<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Filters\EventFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\EventResource;
use App\Http\Requests\StoreEventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the Event resource. Optionally, a User can
     * filter the listing through query params.
     *
     * @param \App\Filters\EventFilter $filters
     *
     * @return \App\Http\Resources\EventResource
     */
    public function index(EventFilter $filters)
    {
        $events = Event::with('category', 'organiser')
            ->latest()
            ->filter($filters)
            ->paginate(10);

        return EventResource::collection($events);
    }

    /**
     * Store a newly created Event resource in storage.
     *
     * @param \App\Http\Requests\StoreEventRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $this->authorize('create', Event::class);

        $event = Event::create([
            'category_id' => request('category_id'),
            'user_id' => auth()->id(),
            'name' => request('name'),
            'description' => request('description'),
            'location' => request('location'),
            'date' => request('date'),
            'time' => request('time'),
        ]);

        return new EventResource($event->load('organiser', 'category'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Event $event
     *
     * @return \App\Http\Resources\EventResource
     */
    public function show(Event $event)
    {
        return new EventResource($event->load('organiser', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
