<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the Category resource.
     *
     * @return \App\Http\Resources\CategoryResource
     */
    public function index()
    {
        return CategoryResource::collection(Category::get());
    }
}
