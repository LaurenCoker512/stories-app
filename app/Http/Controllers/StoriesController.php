<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;

class StoriesController extends Controller
{
    public function index()
    {
        $stories = Story::all();

        return view('stories.index', compact('stories'));
    }

    public function store()
    {
        // validate

        // persist
        Story::create(request(['title', 'description', 'user_id']));

        // redirect
        return redirect('/stories');
    }
}
