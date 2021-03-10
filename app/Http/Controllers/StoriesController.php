<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;

class StoriesController extends Controller
{
    public function index()
    {
        $stories = Story::latest('updated_at')->get();

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

        $story = auth()->user()->stories()->create($attributes);

        return redirect($story->path());
    }

    public function edit(Story $story)
    {
        $this->authorize('update', $story);

        return view('stories.edit', compact('story'));
    }

    public function update(Story $story)
    {
        $this->authorize('update', $story);

        $attributes = request()->validate([
            'title' => 'min:1', 
            'description' => 'min:1'
        ]);

        $story->update($attributes);

        return redirect($story->path());
    }
}
