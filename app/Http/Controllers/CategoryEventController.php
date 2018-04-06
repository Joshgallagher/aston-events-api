<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Filters\EventFilter;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;

class CategoryEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Category     $category
     * @param \App\Filters\EventFilter $filters
     *
     * @return \App\Http\Resources\EventResource
     */
    public function index(Category $category, EventFilter $filters)
    {
        $categoryEvents = $category->events()
            ->with('category')
            ->latest()
            ->filter($filters)
            ->paginate(10);

        return EventResource::collection($categoryEvents);
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
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
