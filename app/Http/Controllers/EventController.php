<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Filters\EventFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\EventResource;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

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
            'related_event_id' => request('related_event_id'),
            'name' => request('name'),
            'description' => request('description'),
            'location' => request('location'),
            'date' => request('date'),
            'time' => request('time'),
        ]);

        return new EventResource($event->load('organiser', 'category', 'relatedEvent'));
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
        return new EventResource($event->load('organiser', 'category', 'relatedEvent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateEventRequest $request
     * @param \App\Models\Event                     $event
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $event->update([
            'name' => request('name', $event->name),
            'description' => $request->input('description', $event->description),
            'location' => $request->input('location', $event->location),
            'date' => $request->input('date', $event->date),
            'time' => $request->input('time', $event->time),
        ]);

        return response(null, Response::HTTP_NO_CONTENT);
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
