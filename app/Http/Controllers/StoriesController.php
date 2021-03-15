<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Tag;

class StoriesController extends Controller
{
    public function index()
    {
        $stories = Story::latest('updated_at')->paginate(20);
        $tags = Tag::withCount('stories')->orderBy('stories_count', 'desc')->take(10);

        return view('stories.index', compact('stories', 'tags'));
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

        return redirect($story->firstChapterPath());
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
            'title' => 'sometimes|required|min:1', 
            'description' => 'sometimes|required|min:1'
        ]);

        $story->update($attributes);

        return redirect($story->firstChapterPath());
    }

    public function destroy(Story $story)
    {
        $this->authorize('update', $story);

        $story->delete();

        return redirect('/dashboard/' . auth()->id());
    }
}
