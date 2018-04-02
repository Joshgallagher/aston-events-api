<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    /**
     * Display a listing of the Event resource.
     *
     * @return \App\Http\Resources\EventResource
     */
    public function index()
    {
        $events = Event::with('category')->latest()->paginate(10);

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
