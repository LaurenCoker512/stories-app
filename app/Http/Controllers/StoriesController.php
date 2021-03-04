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

    public function show(Story $story)
    {
        return view('stories.show', compact('story'));
    }

    public function create()
    {
        return view('stories.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required'
        ]);

        auth()->user()->stories()->create($attributes);

        return redirect('/stories');
    }

    public function edit(Story $story)
    {
        if (auth()->user()->isNot($story->user)) {
            abort(403);
        }

        return view('stories.edit', compact('story'));
    }
}
