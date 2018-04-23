<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Filters\EventFilter;
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
            ->with('category', 'organiser')
            ->latest()
            ->filter($filters)
            ->paginate(10);

        return EventResource::collection($categoryEvents);
    }
}
